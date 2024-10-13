<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: Client.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 8:53 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */
namespace Wanpeninsula\AliDrop\Api;

require_once "lib/ae-php-sdk/IopSdk.php";

use IopClient;
use IopRequest;
use Wanpeninsula\AliDrop\Contracts\ApiClientInterface;
use Wanpeninsula\AliDrop\Exceptions\ApiException;
use Wanpeninsula\AliDrop\Exceptions\ValidationException;
use Wanpeninsula\AliDrop\Helpers\Validator;
use Wanpeninsula\AliDrop\Traits\LoggerTrait;


class Client implements ApiClientInterface
{
    use LoggerTrait;

    /**
     * @var string
     * @access private
     * Base URL for the API
     * @since 1.0.0
     */
    private string $url = 'https://api-sg.aliexpress.com/sync';

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

    private ?IopClient $client;

    private ?IopRequest $request;

    private static array $allowedRequestNames = [
        'aliexpress.ds.order.create',
        'aliexpress.ds.category.get',
        'aliexpress.trade.buy.placeorder',
        'aliexpress.ds.order.tracking.get',
        'aliexpress.ds.feedname.get',
        'aliexpress.ds.recommend.feed.get',
        'aliexpress.ds.feed.itemids.get',
        'aliexpress.logistics.buyer.freight.calculate',
        'aliexpress.ds.address.get',
        'aliexpress.ds.freight.query',
        'aliexpress.logistics.ds.trackinginfo.query',
        'aliexpress.ds.image.search',
        'aliexpress.trade.ds.order.get',
        'aliexpress.ds.member.benefit.get',
        'aliexpress.ds.product.specialinfo.get',
        'aliexpress.ds.product.get',
        'aliexpress.ds.text.search'
    ];

    /**
     * Client constructor.
     * @param string $appKey
     * @param string $secretKey
     * @throws ApiException
     */
    public function __construct(string $appKey, string $secretKey)
    {
        $this->appKey = $appKey;
        $this->secretKey = $secretKey;
        try {
            $this->client = new IopClient($this->url, $this->appKey, $this->secretKey);
            $this->client->readTimeout = '6';
        }
        catch (\Exception $e) {
            $this->logError("Failed to initialize the API client", ['exception' => $e->getMessage()]);
            throw new ApiException("Failed to initialize the API client", 0, $e);
        }
    }

    /**
     * @param string $name
     * @return $this
     * @throws ApiException|ValidationException
     */
    public function requestName(string $name): static
    {
        try {
            Validator::validateInList($name, self::$allowedRequestNames, 'request_name');
        } catch (ValidationException $e) {
            $this->logError("Validation failed for request name", ['exception' => $e->getMessage(), 'extra' => $e->getExtra()]);
            throw $e;
        }
        try {
            $this->request = new IopRequest($name);
        }
        catch (\Exception $e) {
            $this->logError("Failed to create a request", ['exception' => $e->getMessage()]);
            throw new ApiException("Failed to create a request", 0, $e);
        }

        return $this;
    }

    /**
     * @param array $params
     * @param array $validationRules
     * @return $this
     * @throws ApiException|ValidationException
     */
    public function requestParams(array $params, array $validationRules = []): static
    {
        if (!empty($validationRules)) {
            try {
                Validator::validateMultiple($params, $validationRules);
            } catch (ValidationException $e) {
                $this->logError("Validation failed for request parameters", ['exception' => $e->getMessage(), 'extra' => $e->getExtra()]);
                throw $e;
            }
        }

        if ($this->request) {
            foreach ($params as $key => $value) {
               $this->requestParam($key, $value);
            }
        }
        return $this;
    }


    /**
     * @param string $key
     * @param string|int $value
     * @throws ApiException
     */
     function requestParam(string $key, string|int $value): void
     {
        if ($this->request) {
            try {
                $this->request->addApiParam($key, $value);
            } catch (\Exception $e) {
                $this->logError("Failed to set request parameter", ['exception' => $e->getMessage()]);
                throw new ApiException("Failed to set request parameter", 0, $e);
            }
        }
    }

    /**
     * @param string $timeout
     * @return $this
     */
    public function readTimeout(string $timeout): static
    {
        if ($this->client) {
            $this->client->readTimeout = $timeout;
        }
        else {
            $this->logWarning("Client not initialized");
        }

        return $this;
    }

    /**
     * Execute the request
     * @return bool|string
     * @throws ApiException
     */
    public function execute(): bool|string
    {
        if ($this->client && $this->request) {
            try {
                return $this->client->execute($this->request, $this->getAccessToken());
            }
            catch (\Exception $e) {
                $this->logError("Failed to execute the request", ['exception' => $e->getMessage()]);
                throw new ApiException("Failed to execute the request", 0, $e);
            }
        }
        else {
            $this->logWarning("Client or request not initialized");
        }

        return false;
    }

    /**
     * Set the base URL for the API
     * @param string $url
     */
    public function setBaseUrl(string $url): void
    {
        $this->url = $url;
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
     * Get access token
     * @return string
     * @throws ApiException
     */
    public function getAccessToken(): string
    {
        $tokenFile = 'access.json';

        if (file_exists($tokenFile)) {
            $token = json_decode(file_get_contents($tokenFile), true);
            if ($token['expire_time'] > time()) {
                return $token['access_token'];
            }
            elseif ($token['refresh_time'] > time()) {
                return $this->refreshAccessToken($token['refresh_token']);
            }

            else {
                return $this->generateAccessToken();
            }
        }

        return $this->generateAccessToken();


    }


    /**
     * Generate the access token
     * @return string
     * @throws ApiException
     */
    public function generateAccessToken(): string
    {
        $tokenFile = 'access.json';

        if (file_exists($tokenFile)) {
            $token = json_decode(file_get_contents($tokenFile), true);
            if ($token['expire_time'] > time()) {
                return $token['access_token'];
            }
        }

        try {
            $client = new IopClient($this->tokenUrl, $this->appKey, $this->secretKey);

            $request = new IopRequest('/auth/token/create');
            $request->addApiParam('code','3_509710_qJBcpyjTyppoNu7PObr6xX4C1132');

            $this->logInfo("Requesting access token", ['request' => $request]);
            $response = $client->execute($request);

            $response = json_decode($response, true);

            if (isset($response['access_token'])) {
                $token = [
                    'access_token' => $response['access_token'],
                    'expire_time' => time() + $response['expire_time'],
                    'refresh_token' => $response['refresh_token'],
                    'refresh_time' => time() + ($response['refresh_expires_in'] * 1000)
                ];

                file_put_contents($tokenFile, json_encode($token));

                return $response['access_token'];
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
     * Refresh the access token
     * @param string $refreshToken
     * @return string
     * @throws ApiException
     */
    public function refreshAccessToken(string $refreshToken): string
    {
        $tokenFile = 'access.json';

        if (file_exists($tokenFile)) {
            $token = json_decode(file_get_contents($tokenFile), true);
            if ($token['refresh_time'] > time()) {
                return $token['access_token'];
            }
        }

        try {
            $client = new IopClient($this->tokenUrl, $this->appKey, $this->secretKey);

            $request = new IopRequest('/auth/token/refresh');
            $request->addApiParam('refresh_token', $refreshToken);

            $response = $client->execute($request);

            $response = json_decode($response, true);

            if (isset($response['access_token'])) {
                $token = [
                    'access_token' => $response['access_token'],
                    'expire_time' => time() + $response['expire_time'],
                    'refresh_token' => $response['refresh_token'],
                    'refresh_time' => time() + ($response['refresh_expires_in'] * 1000)
                ];

                file_put_contents($tokenFile, json_encode($token));

                return $response['access_token'];
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

}
