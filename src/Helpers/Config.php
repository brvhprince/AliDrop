<?php
/**
 * Project: AliDrop
 * File: Config.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 18/10/2024 at 11:14 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Helpers;

use Wanpeninsula\AliDrop\Exceptions\ApiException;

class Config
{
    protected array $config;

    /**
     * @throws ApiException
     */
    public function __construct()
    {
        $this->config = $this->loadConfig();
        return $this;
    }

    /**
     * Load the config.php from the project root directory.
     *
     * @return array
     * @throws ApiException if the config.php is not found.
     */
    protected function loadConfig(): array
    {
        // Locate the project root dynamically
        $projectRoot = $this->findProjectRoot();

        $configPath = $projectRoot . DIRECTORY_SEPARATOR . 'alidrop.php';

        if (!file_exists($configPath)) {
            throw new ApiException("Configuration file 'alidrop.php' was not found in the project root.", 404);
        }

        return include $configPath;
    }

    /**
     * Find the root directory of the project.
     *
     * @return string
     */
    protected function findProjectRoot(): string
    {
        // Assuming your package is installed via Composer, and `vendor` is present
        $vendorDir = dirname(__DIR__, 2);

        // Check if we're inside `vendor` and navigate up to the project root
        if (is_dir($vendorDir . '/vendor')) {
            return $vendorDir;
        }

        // Fallback to current working directory (getcwd)
        return getcwd();
    }

    /**
     * Get the loaded configuration array.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
