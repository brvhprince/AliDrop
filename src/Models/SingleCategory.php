<?php
/**
 * Project: AliDrop
 * File: SingleCategory.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 15/10/2024 at 9:01 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Pennycodes\AliDrop\Models;

class SingleCategory
{
    /**
     * @var  array{name: string, id: int}
     */
    public array $category;

    /**
     * @param array<int, array{category_name: string, category_id: int, parent_category_id?: int}> $categories
     */
    public function __construct(array $categories)
    {
        $data = $categories[0];
        $this->category = [
            'name' => $data['category_name'] ?? '',
            'id' => $data['category_id'] ?? 0,
            'parent_category_id' => $data['parent_category_id'] ?? null
        ];
    }

    public function toArray(): array
    {
        return $this->category;
    }
}
