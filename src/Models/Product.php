<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: Product.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 10:48 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Models;

class Product
{
    public string $id;
    public string $name;
    public string $price;
    public string $stock;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->price = $data['price'];
        $this->stock = $data['stock'];
    }
}
