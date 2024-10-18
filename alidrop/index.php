<?php
/**
 * Project: AliDrop
 * File: index.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 18/10/2024 at 2:02 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

$path  =  $_SERVER['PATH_INFO'] ?? null;

if ($path === null) {
    echo "Welcome to AliDrop";
    exit;
}

echo $path;
