<?php
/**
 * Project: AliDrop
 * File: TrackingDetails.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 16/10/2024 at 2:19 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Models;
date_default_timezone_set('GMT');
class TrackingDetails
{
    /**
     * @var list<array{
     *     date: string,
     *     description: string,
     *     name: string,
     * }> List of updated tracking details
     */
    public array $update_list;
    /**
     * @var string The tracking number
     */
    public string $tracking_number;
    /**
     * @var string Carrier name
     */
    public string $carrier_name;
    /**
     * @var string Carrier Estimated delivery time
     */
    public string $carrier_eta;
    /**
     * @var array{
     *     id: int,
     *     sku_description: string,
     *     quantity: int,
     *     title: string
     * } First package item
     */
    public array $first_package_item;

    /**
     * @param array{
     *     detail_node_list: array{
     *         detail_node: list<array{
     *        time_stamp: int,
     *         tracking_detail_desc: string,
     *         tracking_name: string
     *    }>
     *     },
     *     package_item_list: array{
     *         package_item: list<array{
     *          sku_desc: string,
     *          quantity: int,
     *          item_id: int,
     *          item_title: string
     *      }>
     *     },
     *     carrier_name: string,
     *     mail_no: string,
     *     eta_time_stamps: int
     * } $trackingDetails
     */
    public function __construct(array $trackingDetails)
    {
        $nodes = $trackingDetails['detail_node_list']['detail_node'];

        foreach ($nodes as $node) {
            $this->update_list[] = [
                'date' => date('M d, Y \a\t H:i \G\M\T', floor($node['time_stamp']/1000)),
                'description' => $node['tracking_detail_desc'],
                'name' => $node['tracking_name']
            ];
        }

        $this->tracking_number = $trackingDetails['mail_no'];
        $this->carrier_name = $trackingDetails['carrier_name'];
        $this->carrier_eta = date('M d, Y \a\t H:i \G\M\T', floor($trackingDetails['eta_time_stamps']/1000));

        $packageItems = $trackingDetails['package_item_list']['package_item'][0];
        $this->first_package_item = [
            'id' => $packageItems['item_id'],
            'sku_description' => $packageItems['sku_desc'],
            'quantity' => $packageItems['quantity'],
            'title' => $packageItems['item_title']
        ];
    }

}
