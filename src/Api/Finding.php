<?php

namespace Ions\Ebay\Api;

use Ions\Ebay\Api;
use Ions\Ebay\ResultSet;

/**
 * Class Finding
 * @package Ions\Ebay\Api
 */
class Finding extends Api
{
    const LIMIT = 100;

    const OUTPUT_SELECTOR = [
        'AspectHistogram' => false,
        'CategoryHistogram' => false,
        'ConditionHistogram' => false,
        'GalleryInfo' => false,
        'PictureURLLarge' => false,
        'PictureURLSuperSize' => false,
        'UnitPriceInfo' => false,
        'StoreInfo' => false,
        'SellerInfo' => false
    ];

    /**
     * @var string
     */
    public $version = '1.13.0';
    /**
     * @var string
     */
    public $url = 'http://svcs.';
    /**
     * @var string
     */
    public $endpoint = '/services/search/FindingService/v1';
    /**
     * @var string
     */
    public $xmlns = 'http://www.ebay.com/marketplace/search/v1/services';

    /**
     * @var
     */
    public static $global_id;

    /**
     * Finding constructor.
     * @param $url
     * @param $accessKey
     */
    public function __construct($url, $accessKey)
    {
        $this
            ->setUrl($this->url.$url)
            ->setHeaders([
                'Content-Type' => 'text/xml;charset=utf-8',
                'X-EBAY-SOA-REQUEST-DATA-FORMAT' => 'XML',
                'X-EBAY-SOA-SECURITY-APPNAME' => $accessKey,
                'X-EBAY-SOA-SERVICE-VERSION' => $this->version
            ]);
    }

    /**
     * @param $name
     * @param $body
     * @return mixed
     */
    public function config($name, $body)
    {
        $config['header']['X-EBAY-SOA-OPERATION-NAME'] = $name;
        $config['header']['X-EBAY-SOA-GLOBAL-ID'] = static::$global_id;

        $config['url'] = $this->endpoint;
        $config['method'] = $this->method;
        $config['body'] = $body;

        return $config;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return (string)$this->call('getVersion')->version;
    }

    /**
     * @param $categoryId
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function getHistograms($categoryId)
    {
        return new ResultSet($this->call('getHistograms', [
            'categoryId' => $categoryId
        ]));
    }

    /**
     * @param $keywords
     * @param array $pagination
     * @param array $item_filter
     * @param string $sort_order
     * @param string $output_selector
     * @param string $postal_code
     * @param array $aspect_filter
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function findItemsByKeywords($keywords, array $pagination, array $item_filter = [], $sort_order = '', $output_selector = '', $postal_code = '', $aspect_filter = [])
    {
        return new ResultSet($this->call('findItemsByKeywords', [
            'keywords' => $keywords,
            'paginationInput' => $pagination,
            'itemFilter' => $item_filter,
            'sortOrder' => $sort_order,
            'outputSelector' => $output_selector,
            'buyerPostalCode' => $postal_code,
            'aspectFilter' => $aspect_filter
        ]));
    }

    /**
     * @param $categoryId
     * @param array $pagination
     * @param array $item_filter
     * @param string $sort_order
     * @param string $output_selector
     * @param string $postal_code
     * @param array $aspect_filter
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function findItemsByCategory($categoryId, array $pagination, array $item_filter = [], $sort_order = '', $output_selector = '', $postal_code = '', $aspect_filter = [])
    {
        return new ResultSet($this->call('findItemsByCategory', [
            'categoryId' => $categoryId,
            'paginationInput' => $pagination,
            'itemFilter' => $item_filter,
            'sortOrder' => $sort_order,
            'outputSelector' => $output_selector,
            'buyerPostalCode' => $postal_code,
            'aspectFilter' => $aspect_filter
        ]));
    }

    /**
     * @param $productId
     * @param array $pagination
     * @param array $item_filter
     * @param string $sort_order
     * @param string $output_selector
     * @param string $postal_code
     * @param array $aspect_filter
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function findItemsByProduct($productId, array $pagination, array $item_filter = [], $sort_order = '', $output_selector = '', $postal_code = '', $aspect_filter = [])
    {
        return new ResultSet($this->call('findItemsByProduct', [
            'productId' => $productId,
            'paginationInput' => $pagination,
            'itemFilter' => $item_filter,
            'sortOrder' => $sort_order,
            'outputSelector' => $output_selector,
            'buyerPostalCode' => $postal_code,
            'aspectFilter' => $aspect_filter
        ]));
    }

    /**
     * @param $categoryId
     * @param $keywords
     * @param array $pagination
     * @param array $item_filter
     * @param string $sort_order
     * @param string $output_selector
     * @param string $postal_code
     * @param array $aspect_filter
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function findItemsAdvanced($categoryId, $keywords, array $pagination, array $item_filter = [], $sort_order = '', $output_selector = '', $postal_code = '', $aspect_filter = [])
    {
        return new ResultSet($this->call('findItemsAdvanced', [
            'categoryId' => $categoryId,
            'keywords' => $keywords,
            'paginationInput' => $pagination,
            'itemFilter' => $item_filter,
            'sortOrder' => $sort_order,
            'outputSelector' => $output_selector,
            'buyerPostalCode' => $postal_code,
            'descriptionSearch' => true,
            'aspectFilter' => $aspect_filter
        ]));
    }

    /**
     * @param $categoryId
     * @param $keywords
     * @param array $pagination
     * @param array $item_filter
     * @param string $sort_order
     * @param string $output_selector
     * @param string $postal_code
     * @param array $aspect_filter
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function findCompletedItems($categoryId, $keywords, array $pagination, array $item_filter = [], $sort_order = '', $output_selector = '', $postal_code = '', $aspect_filter = [])
    {
        return new ResultSet($this->call('findCompletedItems', [
            'categoryId' => $categoryId,
            'keywords' => $keywords,
            'paginationInput' => $pagination,
            'itemFilter' => $item_filter,
            'sortOrder' => $sort_order,
            'outputSelector' => $output_selector,
            'buyerPostalCode' => $postal_code,
            'aspectFilter' => $aspect_filter
        ]));
    }

    /**
     * @param $storeName
     * @param $keywords
     * @param array $pagination
     * @param array $item_filter
     * @param string $sort_order
     * @param string $output_selector
     * @param string $postal_code
     * @param array $aspect_filter
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function findItemsIneBayStores($storeName, $keywords, array $pagination, array $item_filter = [], $sort_order = '', $output_selector = '', $postal_code = '', $aspect_filter = [])
    {
        return new ResultSet($this->call('findItemsIneBayStores', [
            'storeName' => $storeName,
            'keywords' => $keywords,
            'paginationInput' => $pagination,
            'itemFilter' => $item_filter,
            'sortOrder' => $sort_order,
            'outputSelector' => $output_selector,
            'buyerPostalCode' => $postal_code,
            'aspectFilter' => $aspect_filter
        ]));
    }

    /**
     * @param $keywords
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function getSearchKeywordsRecommendation($keywords)
    {
        return new ResultSet($this->call('getSearchKeywordsRecommendation', [
            'keywords' => $keywords
        ]));
    }
}
