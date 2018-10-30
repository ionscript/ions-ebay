<?php

namespace Ions\Ebay;

use Ions\Ebay\Api\Finding;
use Ions\Ebay\Api\Shopping;
use Ions\Ebay\Api\Trading;

/**
 * Class Ebay
 * @package Ions\Ebay
 */
class Ebay
{
    const SERVER = 'ebay.com';
    const SANDBOX = 'sandbox.ebay.com';

    private $api = [
        'finding'
    ];

    /**
     * @var Finding
     */
    private $finding;

    /**
     * @var Shopping
     */
    private $shopping;
    /**
     * @var Trading
     */
    private $traiding;
    /**
     * @var
     */
    private static $data;
    /**
     * @var
     */
    private static $total;
    /**
     * @var
     */
    private static $pages;
    /**
     * @var
     */
    private static $page;
    /**
     * @var
     */
    private static $error;

    /**
     * Ebay constructor.
     * @param $settings
     */
    public function __construct($settings)
    {
        $api = new \Ions\Http\Client\Api();

        $finding = new Finding(
            $server,
            $settings['key']
        );


        $api->set('finding', 'Finding::findItemsByKeywordsRequest');

//        $server = isset($settings['sandbox']) && $settings['sandbox'] ? static::SANDBOX : static::SERVER;
//
//        $this->finding = new Finding(
//            $server,
//            $settings['key']
//        );
//
//        $this->shopping = new Shopping(
//            $server,
//            $settings['key']
//        );
//
//        $this->traiding = new Trading(
//            $server,
//            $settings['token']
//        );
    }

    /**
     * @param array $data
     * @return $this
     */
    public function getItemsByCategory(array $data)
    {
        Finding::$global_id = $data['global_id'];

        if ($data['category']) {

            $result = $this->finding->findItemsByCategory(
                $data['category'],
                [
                    'pageNumber' => $data['page'],
                    'entriesPerPage' => !empty($data['limit']) ? $data['limit'] : Finding::LIMIT
                ],
                $data['item_filter'],
                $data['sort_order']
            );

            if ($result->isSuccess()) {
                static::$data = $result->items();
                static::$total = $result->total();
                static::$pages = $result->pages();
                static::$page = $result->page();
            } else {
                static::$error = $result->error();
            }
        }

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function getItemsByKeywords(array $data)
    {
        Finding::$global_id = $data['global_id'];

        if ($data['keywords']) {

            $result = $this->finding->findItemsByKeywords(
                $data['keywords'],
                [
                    'pageNumber' => $data['page'],
                    'entriesPerPage' => !empty($data['limit']) ? $data['limit'] : Finding::LIMIT
                ],
                $data['item_filter'],
                $data['sort_order']
            );

            if ($result->isSuccess()) {
                static::$data = $result->items();
                static::$total = $result->total();
                static::$pages = $result->pages();
                static::$page = $result->page();
            } else {
                static::$error = $result->error();
            }
        }

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function getItemsAdvanced(array $data)
    {
        Finding::$global_id = $data['global_id'];

        if ($data['category'] || $data['keywords']) {

            $result = $this->finding->findItemsAdvanced(
                $data['category'] ?: '',
                $data['keywords'] ?: '',
                [
                    'pageNumber' => $data['page'],
                    'entriesPerPage' => !empty($data['limit']) ? $data['limit'] : Finding::LIMIT
                ],
                $data['item_filter'],
                $data['sort_order']
            );

            if ($result->isSuccess()) {
                static::$data = $result->items();
                static::$total = $result->total();
                static::$pages = $result->pages();
                static::$page = $result->page();
            } else {
                static::$error = $result->error();
            }
        }

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function getItems(array $data)
    {
        Finding::$global_id = $data['global_id'];

        if ($data['category'] && $data['keywords']) {
            $this->getItemsAdvanced($data);
        } elseif ($data['category']) {
            $this->getItemsByCategory($data);
        } elseif ($data['keywords']) {
            $this->getItemsByKeywords($data);
        }

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function getListing(array $data)
    {
        Shopping::$site_id = $data['site_id'];

        $ids = array_chunk($data['ids'], Shopping::LIMIT);

        foreach ($ids as $i => $id) {

            $result = $this->shopping->getMultipleItems($id);
            if ($result->isSuccess()) {
                static::$data = $result->listing();
                static::$total = count($data['ids']);
                static::$pages = count($ids);
                static::$page = $i;
            } else {
                static::$error = $result->error();
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function getCategories()
    {
        Trading::$site_id = 0;

        $result = $this->traiding->getCategories();

        if ($result->isSuccess()) {
            static::$data = $result->category();
            static::$total = $result->total();
        } else {
            static::$error = $result->error();
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public static function getTotal()
    {
        return self::$total;
    }

    /**
     * @return mixed
     */
    public static function getPages()
    {
        return self::$pages;
    }

    /**
     * @return mixed
     */
    public static function getPage()
    {
        return self::$page;
    }

    /**
     * @return mixed
     */
    public static function getError()
    {
        return self::$error;
    }

    /**
     * @param bool $array
     * @return mixed
     */
    public function getData($array = true)
    {
        return json_decode(json_encode(static::$data), $array);
    }

    /**
     * @param $time
     * @return mixed
     */
    public function getTimeLeft($time)
    {
        preg_match('#P([0-9]{0,3}D)?T([0-9]?[0-9]H)?([0-9]?[0-9]M)?([0-9]?[0-9]S)#msiU', $time, $matches);

        return $matches;
    }
}
