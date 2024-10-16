<?php
/**
 * Project: AliDrop
 * File: ExternalOrderItem.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 16/10/2024 at 10:17 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Models;

class ExternalOrderItem
{
    /**
     * External product ID
     * @var int
     */
    private int $productId;

    /**
     * External product SKU ID
     * @var string
     */
    private string $skuId;
    /**
     * Quantity of the product
     * @var int
     */
    private int $quantity;

    /**
     * Logistics service name
     * @var ?string
     */
    private ?string $logisticsServiceName;

    /**
     * Order comment
     * @var ?string
     */
    private ?string $comments;

    /**
     * ExternalOrderItem constructor.
     * @param int $productId
     * @param string $skuId
     * @param int $quantity
     * @param string|null $logisticsServiceName
     * @param string|null $comments
     */
    public function __construct(int $productId, string $skuId, int $quantity, ?string $logisticsServiceName = null, ?string $comments = null)
    {
        $this->productId = $productId;
        $this->skuId = $skuId;
        $this->quantity = $quantity;
        $this->logisticsServiceName = $logisticsServiceName;
        $this->comments = $comments;
    }

    /**
     * Get the product ID
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * Get the product SKU ID
     * @return string
     */
    public function getSkuId(): string
    {
        return $this->skuId;
    }

    /**
     * Get the product quantity
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Get the logistics service name
     * @return string|null
     */
    public function getLogisticsServiceName(): ?string
    {
        return $this->logisticsServiceName;
    }

    /**
     * Get the order comments
     * @return string|null
     */
    public function getComments(): ?string
    {
        return $this->comments;
    }
}
