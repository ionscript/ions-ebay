<?php

namespace Ions\Ebay\Api;

use Ions\Ebay\Api;
use Ions\Ebay\ResultSet;

/**
 * Class Shopping
 * @package Ions\Ebay\Api
 */
class Shopping extends Api
{
//    const LIMIT = 20;
//
//    const INCLUDE_SELECTOR = [
//        'Details' => true,
//        'Description' => true,
//        'TextDescription' => false,
//        'ShippingCosts' => false,
//        'ItemSpecifics' => true,
//        'Variations' => true,
//        'Compatibility' => false
//    ];

    /**
     * @var array
     */
    public $includeSelector = [];

    /**
     * @var string
     */
    public $version = '863';
    /**
     * @var string
     */
    public $url = 'http://open.api.';
    /**
     * @var string
     */
    public $endpoint = '/shopping';
    /**
     * @var string
     */
    public $xmlns = 'urn:ebay:apis:eBLBaseComponents';

    /**
     * @var
     */
    public static $site_id;

    /**
     * Shopping constructor.
     * @param $url
     * @param $accessKey
     */
    public function __construct($url, $accessKey)
    {
        $this->includeSelector = array_keys(static::INCLUDE_SELECTOR, true);

        $this
            ->setUrl($this->url . $url)
            ->setHeaders([
                'Content-Type' => 'text/xml;charset=utf-8',
                'X-EBAY-API-REQUEST-ENCODING' => 'xml',
                'X-EBAY-API-APP-ID' => $accessKey,
                'X-EBAY-API-VERSION' => $this->version
            ]);
    }

    /**
     * @param $name
     * @param $body
     * @return mixed
     */
    public function config($name, $body)
    {
        $config['header']['X-EBAY-API-CALL-NAME'] = $name;
        $config['header']['X-EBAY-API-SITE-ID'] = static::$site_id;

        $config['url'] = $this->endpoint;
        $config['method'] = $this->method;
        $config['body'] = $body;

        return $config;
    }

    /**
     * @param $categoryId
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function getCategoryInfo($categoryId)
    {
        return new ResultSet($this->call('GetCategoryInfo', [
            'categoryID' => $categoryId
        ]));
    }

    /**
     * @param $keywords
     * @param $limit
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function findHalfProducts($keywords, $limit)
    {
        return new ResultSet($this->call('FindHalfProducts', [
            'queryKeywords' => $keywords,
            'maxEntries' => $limit
        ]));
    }

    /**
     * @param $keywords
     * @param $page
     * @param $limit
     * @param $domain
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function findProductsByKeywords($keywords, $page, $limit, $domain)
    {
        return new ResultSet($this->call('FindProducts', [
            'queryKeywords' => $keywords,
            'domainName' => $domain,
            'pageNumber' => $page,
            'maxEntries' => $limit,
            'availableItemsOnly' => 'true',
            'includeSelector' => 'DomainHistogram'
        ]));
    }

    /**
     * @param $product_id
     * @param $page
     * @param $limit
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function findProductsByProduct($product_id, $page, $limit)
    {
        return new ResultSet($this->call('FindProducts', [
            'productID' => [$product_id => ['type'=>'Reference']],
            'pageNumber' => $page,
            'maxEntries' => $limit,
            'availableItemsOnly' => 'true',
            'includeSelector' => implode(',', $this->includeSelector)
        ]));
    }

    /**
     * @return mixed
     */
    public function getEbayTime()
    {
        return $this->call('GeteBayTime')->Timestamp;
    }

    /**
     * @param array $ids
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function getItemStatus(array $ids)
    {
        return new ResultSet($this->call('GetItemStatus', [
            'itemID' => $ids,
            'includeSelector' => implode(',', $this->includeSelector)
        ]));
    }

    /**
     * @param array $ids
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function getMultipleItems(array $ids)
    {
        return new ResultSet($this->call('GetMultipleItems', [
            'itemID' => $ids,
            'includeSelector' => implode(',', $this->includeSelector)
        ]));
    }

    /**
     * @param $itemId
     * @return ResultSet
     * @throws \RuntimeException
     */
    public function getSingleItem($itemId)
    {
        return new ResultSet($this->call('GetSingleItem', [
            'itemID' => [$itemId],
            'includeSelector' => implode(',', $this->includeSelector)
        ]));
    }
}
