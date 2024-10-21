<?php
/**
 * Project: AliDrop
 * File: Token.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 18/10/2024 at 11:36 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Pennycodes\AliDrop\Api;
require_once __DIR__. "/../../lib/ae-php-sdk/IopSdk.php";


use IopClient;
use IopRequest;
use mysqli;
use Pennycodes\AliDrop\Exceptions\ApiException;
use Pennycodes\AliDrop\Helpers\Config;
use Pennycodes\AliDrop\Traits\LoggerTrait;

class Token
{
    use LoggerTrait;

    /**
     * @var string Access token
     */
    public string $access_token;
    /**
     * @var int Access token expiration time
     */
    public int $access_token_expire_time;
    /**
     * @var string Refresh token
     */
    public string $refresh_token;
    /**
     * @var int Refresh token expiration time
     */
    public int $refresh_token_expire_time;

    /**
     * @var array Configuration settings
     */
    private array $config;

    /**
     * @var string
     * @access private
     * Base URL for fetching the access token
     * @since 1.0.0
     */
    private string $tokenUrl = 'https://api-sg.aliexpress.com/rest';
    /**
     * @var string
     * @access private
     * <p>Get it from the Aliexpress App Console (https://openservice.aliexpress.com) </p>
     * under App Overview after creating an app.
     * @since 1.0.0
     */
    protected string $appKey;

    /**
     * @var string
     * @access private
     * <p>Get it from the Aliexpress App Console (https://openservice.aliexpress.com) </p>
     * under App Overview after creating an app.
     * @since 1.0.0
     */
    protected string $secretKey;
    /**
     * @var string
     * @access protected
     * <p>If set will override config.callback_url</p>
     * @since 1.1.0
     */
    protected ?string $callback_url;

    /**
     * @var false|mysqli $db
     */
    private static false|mysqli $db;

    /**
     * Token constructor.
     * @param string $appKey
     * @param string $secretKey
     */
    public function __construct(string $appKey, string $secretKey)
    {
        $this->appKey = $appKey;
        $this->secretKey = $secretKey;

        // initialize empty
        $this->access_token = '';
        $this->access_token_expire_time = 0;
        $this->refresh_token = '';
        $this->refresh_token_expire_time = 0;
        $this->callback_url = null;
        $config = new Config();
        $this->config = $config->getConfig();

        $this->init();
    }

    private function init(): void
    {
        try {
            if ($this->config['token_storage'] === 'file') {
                $this->loadFromFile();
            } else {
                $this->loadFromDb();
            }
        }
        catch (\Exception $e) {
            $this->logError("Failed to initialize the token", ['exception' => $e->getMessage()]);
        }
    }

    /**
     * @throws ApiException
     */
    private function loadFromFile(): void
    {
        $tokenFile = $this->config['token_file'];

        if (file_exists($tokenFile)) {
            $token = json_decode(file_get_contents($tokenFile), true);
            if ($token['expire_time'] > time()) {
                $this->access_token = $token['access_token'];
                $this->access_token_expire_time = $token['expire_time'];
                $this->refresh_token = $token['refresh_token'];
                $this->refresh_token_expire_time = $token['refresh_time'];
            }
            elseif ($token['refresh_time'] > time()) {
                $this->refreshAccessToken($token['refresh_token'], "file");
            }
        }
        else {
            $this->logError("You have not generated an access token yet. Call aliDropInstance->token-generateAccessLink()");
        }

    }

    /**
     * @throws ApiException
     */
    private function connect(): void
    {
        if (!isset(self::$db)) {
            try {

                $db = mysqli_connect($this->config['db']['host'], $this->config['db']['username'], $this->config['db']['password'], $this->config['db']['database'], $this->config['db']['port']);

                if (!empty($this->config['db']['charset'])) {
                    $db->set_charset($this->config['db']['charset']);
                }
                self::$db = $db;
            } catch (\Exception $e) {
                $this->logError("Failed to connect to the database", ['exception' => $e->getMessage()]);
                throw new ApiException("Failed to connect to the database", 0, $e);
            }
        }
    }

    /**
     * @throws ApiException
     */
    private function loadFromDb(): void
    {
        $this->connect();

        if (!self::$db) {
            $this->logError("Failed to connect to the database");
            throw new ApiException("Failed to connect to the database", 0);
        }

        $this->initializeDb();

        $table = $this->config['db']['table_prefix'] . 'tokens';

        $sql = "SELECT * FROM $table WHERE id = 1";

        $result = self::$db->query($sql);

        if ($result->num_rows > 0) {
            $token = $result->fetch_assoc();
            if ($token['expire_time'] > time()) {
                $this->access_token = $token['access_token'];
                $this->access_token_expire_time = $token['expire_time'];
                $this->refresh_token = $token['refresh_token'];
                $this->refresh_token_expire_time = $token['refresh_time'];
            }
            elseif ($token['refresh_time'] > time()) {
                $this->refreshAccessToken($token['refresh_token'], "db");
            }
        }
    }

    /**
     * @throws ApiException
     */
    private function saveTokenToDb(array $token): void
    {
        $this->connect();

        if (!self::$db) {
            $this->logError("Failed to connect to the database");
            throw new ApiException("Failed to connect to the database", 0);
        }

        $table = $this->config['db']['table_prefix'] . 'tokens';

        // first get access token from the database
        $tmp = "SELECT * FROM $table WHERE id = 1";

        $result = self::$db->query($tmp);

        if ($result->num_rows > 0) {
            $sql = "UPDATE $table SET access_token = ?, expire_time = ?, refresh_token = ?, refresh_time = ? WHERE id = 1";
        }
        else {
            $sql = "INSERT INTO $table (id, access_token, expire_time, refresh_token, refresh_time) VALUES (?, ?, ?, ?, ?)";
        }

        $stmt = self::$db->prepare($sql);
        $id = 1;
        $stmt->bind_param("isisi", $id,$token['access_token'], $token['expire_time'], $token['refresh_token'], $token['refresh_time']);

        if ($stmt->execute() === false) {
            $this->logError("Failed to save the token to the database", ['error' => self::$db->error]);
            throw new ApiException("Failed to save the token to the database", 0);
        }

    }

    /**
     * @throws ApiException
     */
    private function initializeDb(): void
    {

        $table = $this->config['db']['table_prefix'] . 'tokens';

        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            access_token VARCHAR(255) NOT NULL,
            expire_time INT(11) NOT NULL,
            refresh_token VARCHAR(255) NOT NULL,
            refresh_time INT(11) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        if (self::$db->query($sql) === false) {
            $this->logError("Failed to create the tokens table", ['error' => self::$db->error]);
            throw new ApiException("Failed to create the tokens table", 0);
        }

    }

    /**
     * Generate the access token
     * @param string $code - Authorization code from redirect URL
     * @return void
     * @throws ApiException
     */
    public function generateAccessToken(string $code): void
    {
        try {
            $client = new IopClient($this->tokenUrl, $this->appKey, $this->secretKey);

            $request = new IopRequest('/auth/token/create');
            $request->addApiParam('code',$code);

            $response = $client->execute($request);

            $response = json_decode($response, true);
            if (isset($response['access_token'])) {
                $token = [
                    'access_token' => $response['access_token'],
                    'expire_time' => floor($response['expire_time'] / 1000),
                    'refresh_token' => $response['refresh_token'],
                    'refresh_time' => floor($response['refresh_token_valid_time'] / 1000)
                ];

                if ($this->config['token_storage'] === "file") {
                    file_put_contents($this->config['token_file'], json_encode($token));
                }
                else {
                    $this->saveTokenToDb($token);
                }

            }
            else {
                $this->logError("Failed to generate the access token", ['response' => $response]);
                throw new ApiException("Failed to generate the access token", 0);
            }
        }
        catch (\Exception $e) {
            $this->logError("Failed to initialize the token client", ['exception' => $e->getMessage()]);
            throw new ApiException("Failed to initialize the token client", 0, $e);
        }

    }

    /**
     * Generate access link
     * @return string
     */
    public function generateAccessLink(): string

    {
        $redirectUrl = $this->callback_url ?? $this->config['callback_url'];
        return "https://api-sg.aliexpress.com/oauth/authorize?response_type=code&force_auth=true&client_id={$this->appKey}&redirect_uri={$redirectUrl}";
    }

    /**
     * Refresh the access token
     * @param string $refreshToken
     * @param string $type
     * @return void
     * @throws ApiException
     */
    private function refreshAccessToken(string $refreshToken, string $type): void
    {


        try {
            $client = new IopClient($this->tokenUrl, $this->appKey, $this->secretKey);

            $request = new IopRequest('/auth/token/refresh');
            $request->addApiParam('refresh_token', $refreshToken);

            $response = $client->execute($request);

            $response = json_decode($response, true);

            if (isset($response['access_token'])) {
                $token = [
                    'access_token' => $response['access_token'],
                    'expire_time' => floor($response['expire_time'] / 1000),
                    'refresh_token' => $response['refresh_token'],
                    'refresh_time' => floor($response['refresh_token_valid_time'] / 1000)
                ];

               if ($type === "file") {
                   $tokenFile = $this->config['token_file'];

                   // if token file has a directory structure, create the directory if it doesn't exist
                     $tokenFileDir = dirname($tokenFile);
                        if (!file_exists($tokenFileDir)) {
                            mkdir($tokenFileDir, 0777, true);
                        }

                   file_put_contents($tokenFile, json_encode($token));
               }
                else {
                    $this->saveTokenToDb($token);
                }

                 $this->access_token = $response['access_token'];
                 $this->access_token_expire_time = floor($response['expire_time'] / 1000);
                 $this->refresh_token = $response['refresh_token'];
                 $this->refresh_token_expire_time = floor($response['refresh_token_valid_time'] / 1000);
            }
            else {
                $this->logError("Failed to refresh the access token", ['response' => $response]);
                throw new ApiException("Failed to refresh the access token", 0);
            }
        }
        catch (\Exception $e) {
            $this->logError("Failed to initialize the token client", ['exception' => $e->getMessage()]);
            throw new ApiException("Failed to initialize the token client", 0, $e);
        }
    }

    /**
     * Set the base URL for fetching the access token
     * @param string $url
     */
    public function setTokenUrl(string $url): void
    {
        $this->tokenUrl = $url;
    }

    /**
     * Set the callback URL
     */
    public function setCallbackUrl(string $url): void
    {
        $this->callback_url = $url;
    }

}
