<?php
/**
 * Project: AliDrop
 * File: alidrop.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 9:44 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

return [
    'log_level' => 'error',
    'log_file' => 'alidrop.log',
    'log_dir' => 'logs',
    'log_max_files' => 5,
    "callback_url" => "https://yourwebsite.com/alidrop",
    "token_storage" => "file", // file or db
    "token_file" => "token.json",
    "db" => [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'alidrop',
        'username' => 'root',
        'password' => 'root',
        'table_prefix' => 'alidrop_',
        'charset' => 'utf8mb4',
    ],
];
