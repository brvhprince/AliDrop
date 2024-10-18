<?php
/**
 * Project: AliDrop
 * File: OrderDetails.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 16/10/2024 at 12:48 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Pennycodes\AliDrop\Models;

class OrderDetails
{
    /**
     * @var string Date order was placed
     */
    public string $order_date;
    /**
     * @var string Order status
     */
    public string $order_status;
    /**
     * @var array{
     *     seconds: string,
     *     hours: string,
     *     days: string
     * } Payment expiration in hours
     */
    public array $payment_expiration;
    /**
     * @var array{
     *     service: string,
     *     code: string,
     *     status: string,
     * }
     *     Shipping (Logistics) information
     */
    public array $shipping;
    /**
     * @var array{
     *     id: ?string,
     *     name: ?string,
     *     all?: array{
     *         logistics_no: string,
     *          logistics_service: string,
     *     }
     *     url: string
     * }
     *     Store information
     */
    public array $store;
    /**
     * @var array{
     *     amount: number,
     *     currency: string
     * }
     *     order amount in user's payment currency
     */
    public array $user_order_amount;
    /**
     * @var array{
     *     amount: number,
     *     currency: string
     * }
     *     actual order amount
     */
    public array $order_amount;

    /**
     * @var array{
     *     tax: array{
     *     amount: number,
     *     currency: string,
     *     tax_include: boolean
     *     },
     *     shipping: array {
     *         discount: array{
     *          amount: number,
     *          currency: string
     *          },
     *         fee: array{
     *          amount: number,
     *          currency: string
     *          },
     *          actual_fee: array{
     *           amount: number,
     *           currency: string
     *       }
     *     },
     *     sales: array{
     *      discount: array{
     *          amount: number,
     *          currency: string
     *          },
     *      fee: array{
     *          amount: number,
     *           currency: string
     *          },
     *      actual_fee: array{
     *          amount: number,
     *          currency: string
     *          },
     *     },
     *     end_reason?: string,
     *    product: array{
     *     price: array{
     *      amount: number,
     *      currency: string
     *      },
     *     name: string,
     *     quantity: number,
     *     sku_id: string,
     *     id: number
     * }
     *     order items
     */
    public array $order_items;

    /**
     * @param array{
     *     gmt_create: string,
     *     order_status: string,
     *     logistics_info_list: array{
     *     logistics_no: string,
     *     logistics_service: string,
     *     },
     *     pay_timeout_second: string,
     *     store_info: array{
     *     store_id: number,
     *     store_name: string,
     *     store_url: string
     * },
     *     user_order_amount: array{
     *     amount: string,
     *     currency_code: string
     * },
     *     order_amount: array{
     *     amount: string,
     *     currency_code: string
     * },
     *     child_order_list: array{
     *     aeop_child_order_info: list<array{
     *          shipping_discount_fee: array{
     *           amount: string,
     *          currency_code: string
     *          },
     *     sale_fee: array{
     *     amount: string,
     *     currency_code: string
     *   },
     *     actual_fee: array{
     *     amount: string,
     *     currency_code: string
     * },
     *     product_count: int,
     *     sku_id: string,
     *     sale_discount_fee: array{
     *     amount: string,
     *     currency_code: string
     *  },
     *     product_price: array{
     *     amount: string,
     *     currency_code: string
     * },
     *     product_name: string,
     *     shipping_fee: array{
     *     amount: string,
     *     currency_code: string
     * },
     *     actual_shipping_fee: array{
     *     amount: string,
     *     currency_code: string
     * },
     *     product_id: int,
     *      already_include_tax: string,
     *     end_reason?: string,
     *     actual_tax_fee: array{
     *     amount: string,
     *     currency_code: string
     * },
     *     },
     *     },
     *     logistics_status: string
     * } $orderDetails
     */
    public function __construct(array $orderDetails) {
        $this->order_date = $orderDetails['gmt_create'];
        $this->order_status = $orderDetails['order_status'];
        $this->payment_expiration = [
            'seconds' => (int) $orderDetails['pay_timeout_second'],
            'hours' => $orderDetails['pay_timeout_second'] / 3600,
            'days' => $orderDetails['pay_timeout_second'] / 86400
        ];
        $logisticsInfo = $orderDetails['logistics_info_list'];
        if (empty($logisticsInfo)) {
            $this->shipping = [
                'service' => null,
                'code' => null,
                'status' => $orderDetails['logistics_status']
            ];
        }
        else {
            $this->shipping = [
                'service' => $logisticsInfo['aeop_order_logistics_info'][0]['logistics_service'],
                'code' => $logisticsInfo['aeop_order_logistics_info'][0]['logistics_no'],
                'all' => $logisticsInfo['aeop_order_logistics_info'],
                'status' => $orderDetails['logistics_status']
            ];
        }

        $this->store = [
            'id' => $orderDetails['store_info']['store_id'],
            'name' => $orderDetails['store_info']['store_name'],
            'url' => $orderDetails['store_info']['store_url']
        ];
        $this->user_order_amount = [
            'amount' => $orderDetails['user_order_amount']['amount'],
            'currency' => $orderDetails['user_order_amount']['currency_code']
        ];
        $this->order_amount = [
            'amount' => $orderDetails['order_amount']['amount'],
            'currency' => $orderDetails['order_amount']['currency_code']
        ];
        $this->order_items = [];

        foreach ($orderDetails['child_order_list']['aeop_child_order_info'] ?? [] as $item) {
            $this->order_items[] = [
                'tax' => [
                    'amount' => $item['actual_tax_fee']['amount'],
                    'currency' => $item['actual_tax_fee']['currency_code'],
                    'tax_include' => (bool) $item['already_include_tax']
                ],
                'shipping' => [
                    'discount' => [
                        'amount' => $item['shipping_discount_fee']['amount'],
                        'currency' => $item['shipping_discount_fee']['currency_code']
                    ],
                    'fee' => [
                        'amount' => $item['shipping_fee']['amount'],
                        'currency' => $item['shipping_fee']['currency_code']
                    ],
                    'actual_fee' => [
                        'amount' => $item['actual_shipping_fee']['amount'], // actual shipping fee = shippingfee - shippingdiscountfee
                        'currency' => $item['actual_shipping_fee']['currency_code']
                    ]
                ],
                'sales' => [
                    'discount' => [
                        'amount' => $item['sale_discount_fee']['amount'],
                        'currency' => $item['sale_discount_fee']['currency_code']
                    ],
                    'fee' => [
                        'amount' => $item['sale_fee']['amount'],
                        'currency' => $item['sale_fee']['currency_code']
                    ],
                    'actual_fee' => [
                        'amount' => $item['actual_fee']['amount'], // actual fee = sale fee - sale discount + actual shipping fee
                        'currency' => $item['actual_fee']['currency_code']
                    ]
                ],
                'product' => [
                    'price' => [
                        'amount' => $item['product_price']['amount'],
                        'currency' => $item['product_price']['currency_code']
                    ],
                    'name' => $item['product_name'],
                    'quantity' => $item['product_count'],
                    'sku_id' => $item['sku_id'],
                    'id' => $item['product_id']
                ],
                'end_reason' => $item['end_reason'] ?? null // end reason is only available when order is closed
            ];
        }


    }

}
