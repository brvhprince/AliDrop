<?php
/**
 * Project: AliDrop
 * File: SingleProduct.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 13/10/2024 at 9:59 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Models;

class SingleProduct
{
    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

  public function toArray(): array
  {
      return $this->data;
  }
}
