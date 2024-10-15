<?php
/**
 * Project: AliDrop
 * File: Categories.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 15/10/2024 at 9:01 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Models;

class Categories
{
    /**
     * @var list<array{
     *         category_name: string,
     *         category_id: int,
     *         parent_category_id?: int
     *    }>
     */
    private array $categories;

    /**
     * @param array<int, array{category_name: string, category_id: int, parent_category_id?: int}> $categories
     */
    public function __construct(array $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return array<int, array{category_name: string, category_id: int, parent_category_id?: int}>
     */
    public function toArray(): array
    {
        return $this->categories;
    }

    /**
     *  Get the sorted category tree.
     * @return array<int, array{name: string, id: int, sub_categories: array<int, array{name: string, id: int}>}>
     */
    public function get_sorted_categories(): array
    {
        $categoriesMap = [];
        $mainCategories = [];

        foreach ($this->categories as $category) {
            if (empty($category['category_name'])) {
                continue;
            }

            $parentId = $category['parent_category_id'] ?? null;

            if ($parentId === null) {
                $mainCategories[$category['category_id']] = [
                    'name' => $category['category_name'],
                    'id' => $category['category_id'],
                    'sub_categories' => [],
                ];
            } else {
                $categoriesMap[$parentId][] = [
                    'name' => $category['category_name'],
                    'id' => $category['category_id'],
                ];
            }
        }

        foreach ($mainCategories as $id => &$mainCategory) {
            $mainCategory['sub_categories'] = $categoriesMap[$id] ?? [];
        }

        return array_values($mainCategories);
    }
}
