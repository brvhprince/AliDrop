<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: Logger.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 9:47 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Helpers;

use Wanpeninsula\AliDrop\Contracts\LoggerInterface;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class Logger implements LoggerInterface
{
    private static Logger|null $instance = null;
    private MonologLogger $logger;

    private function __construct()
    {
        // Get configuration
        $config = $this->getConfig();

        // Create Monolog logger instance
        $this->logger = new MonologLogger('alidrop');


        $logFile = $config['log_dir'] . DIRECTORY_SEPARATOR . $config['log_file'];

        // Create a rotating log handler
        $handler = new RotatingFileHandler($logFile, intval($config['log_max_files']), Level::Debug);
        $formatter = new LineFormatter(null, null, true, true);
        $handler->setFormatter($formatter);

        // Add handler to Monolog
        $this->logger->pushHandler($handler);

    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Log a message
     * @param $level
     * @param $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }

    /**
     * Log an error message
     * @param $message
     * @param array $context
     * @return void
     */
    public function error($message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    /**
     * Log an info message
     * @param $message
     * @param array $context
     * @return void
     */
    public function info($message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    /**
     * Log a debug message
     * @param $message
     * @param array $context
     * @return void
     */
    public function debug($message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    /**
     * Log a warning message
     * @param $message
     * @param array $context
     * @return void
     */
    public function warning($message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    private function getConfig(): array
    {
        $configPath = __DIR__ . '/../../config/alidrop.php';
        return include $configPath;
    }
}
