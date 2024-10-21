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

namespace Pennycodes\AliDrop\Models;

use Pennycodes\AliDrop\Helpers\Utils;

class SingleProduct
{
    /**
     * @var string Valid video base url. We will this video base url instead of the invalid one
     */
    private static string $valid_video_base_url = "https://video.aliexpress-media.com/play/u/ae_sg_item";
    /**
     * @var string Invalid video base url. For some reason, the video url is not working
     */
    private static string $invalid_video_base_url = "https://cloud.video.taobao.com/play/u";
    /**
     * @var int Product id
     */
    public int $id;
    /**
     * @var string Product name
     */
    public string $name;
    /**
     * @var string Product sanitized description
     */
    public string $description;
    /**
     * @var ProductDetailsStoreInfo Store information
     */
    public ProductDetailsStoreInfo $store_info;
    /**
     * @var ProductDetailsProperties[] Product properties
     */
    public array $properties;
    /**
     * @var ?array{
     *     time: ?int,
     *     country: ?string,
     * } Product shipping lead time
     */
    public ?array $shipping_lead_time;
    /**
     * @var string[] Product images
     */
    public array $images;

    /**
     * @var list<array{
     *     poster: string,
     *     src: string
     * }> Product videos
     */
    public array $videos;

    /**
     * @var ProductDetailsPackagingInfo Product packaging information
     */
    public ProductDetailsPackagingInfo $packaging_info;
    /**
     * @var int Product evaluation count
     */
    public int $evaluation_count;
    /**
     * @var string Product sales count
     */
    public string $sales_count;
    /**
     * @var float Average evaluation rating
     */
    public float $average_rating;
    /**
     * @var string Product status
     * one of [offline,onSelling]
     */
    public string $status;
    /**
     * @var int Main category id
     */
    public int $category;
    /**
     * @var string Product currency
     */
    public string $currency;
    /**
     * @var string Product html description
     */
    public string $html_description;
    /**
     * @var string[] Images extracted from product description
     */
    public array $description_images;
    /**
     * @var ProductDetailsVariation[] Product price variations
     */
    public array $pricing;

    /**
     * @param array $productDetails
     */

    public function __construct(array $productDetails)
    {
        $productInfo = $productDetails['ae_item_base_info_dto'];
        $this->id = $productInfo['product_id'];
        $this->category = $productInfo['category_id'];
        $this->currency = $productInfo['currency_code'];
        $this->average_rating = $productInfo['avg_evaluation_rating'];
        $this->status = $productInfo['product_status_type'];
        $this->sales_count = $productInfo['sales_count'];
        $this->evaluation_count = $productInfo['evaluation_count'];
        $this->name = $productInfo['subject'];
        $this->description = Utils::strip_tags($productInfo['detail']);
        $this->html_description = $productInfo['detail'];
        $media = $this->extract_description_media($productInfo);
        $this->description_images = $media['images'];
        $this->videos = $media['videos'];

        $images = $productDetails['ae_multimedia_info_dto'];
        $this->images = explode(';', $images['image_urls']);

        $this->store_info = new ProductDetailsStoreInfo($productDetails['ae_store_info']);

        $properties = $productDetails['ae_item_properties']['ae_item_property'];

        // filter and remove where attr_name_id if set is -1 or attr_value_id if set is -1
        $properties = array_filter($properties,
            fn($property) => (isset($property['attr_name_id']) && $property['attr_name_id'] !== -1) || (isset($property['attr_value_id']) && $property['attr_value_id'] !== -1));

        $this->properties = [];
        foreach ($properties as $property) {
            $this->properties[] = new ProductDetailsProperties($property);
        }



        // Shipping lead time
        if (!empty($productDetails['logistics_info_dto'])) {
            $this->shipping_lead_time = [
                'time' => $productDetails['logistics_info_dto']['delivery_time'] ?? null,
                'country' => $productDetails['logistics_info_dto']['ship_to_country'] ?? null
            ];
        }
        else {
            $this->shipping_lead_time = null;
        }

        $this->packaging_info = new ProductDetailsPackagingInfo($productDetails['package_info_dto']);

        $variations = $productDetails['ae_item_sku_info_dtos']['ae_item_sku_info_d_t_o'];
        $this->pricing = array_map(fn($variation) => new ProductDetailsVariation($variation), $variations);
    }
    private function extract_description_media(array $productInfo): array
    {
        $returnData = [
            'images' => [],
            'videos' => []
        ];
        try {
            $decodeData = json_decode($productInfo['mobile_detail'], true);
            $moduleList = $decodeData['moduleList'];

            // finding from module list where type is image
            $imageList = array_filter($moduleList, fn($module) => $module['type'] === 'image');
            $images = [];
            foreach ($imageList as $image) {
                $images[] = $image['data']['url'];
            }
            $returnData['images'] = $images;

            // finding from module list where type is media
            $mediaList = array_filter($moduleList, fn($module) => $module['type'] === 'media');
            $videos = [];

            foreach ($mediaList as $media) {
                $video = $media['data']['src'];
                if (str_contains($video, self::$invalid_video_base_url)) {
                    $video = str_replace(self::$invalid_video_base_url, self::$valid_video_base_url, $video);
                }
                $videos[] = [
                    'poster' => $media['data']['previewImage'],
                    'src' => $video
                ];
            }

            $returnData['videos'] = $videos;

            return $returnData;
        }
        catch (\Exception $e) {
            return $returnData;
        }
    }
}
