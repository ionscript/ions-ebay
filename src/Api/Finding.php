<?php

namespace Ions\Ebay\Api;

use Ions\Ebay\ResultSet;
use Ions\Http\Client\Api;

/**
 * Class Finding
 * @package Ions\Ebay\Api
 */
class Finding
{
    const VERSION = '1.13.0';

    const BASE_URL = 'http://svcs.ebay.com';

    public $endpoint = '/services/search/FindingService/v1';

    const XMLNS = 'http://www.ebay.com/marketplace/search/v1/services';

    public $method = 'POST';

    public $globalId = 'US_EBAY';

    protected $accessKey;

    protected $secretKey;



//    /**
//     * @var
//     */
//    public static $global_id;

    /**
     * Finding constructor.
     * @param $url
     * @param $accessKey
     */
//    public function __construct($url, $accessKey)
//    {
    //       $this
//            ->setUrl($this->url.$url)
//            ->setHeaders([
//                'Content-Type' => 'text/xml;charset=utf-8',
//                'X-EBAY-SOA-REQUEST-DATA-FORMAT' => 'XML',
//                'X-EBAY-SOA-SECURITY-APPNAME' => $accessKey,
//                'X-EBAY-SOA-SERVICE-VERSION' => $this->version
//            ]);
//    }


    public function __construct($options)
    {

        $this->api =
            (new Api())
                ->setUrl($this->url)
                ->setHeaders([
                    'Content-Type' => 'text/xml;charset=utf-8',
                    'X-EBAY-SOA-REQUEST-DATA-FORMAT' => 'XML',
                    'X-EBAY-SOA-SECURITY-APPNAME' => $appId,
                    'X-EBAY-SOA-SERVICE-VERSION' => $this->version
                ]);

//        $this->appId = (string) $appId;
//        $this->_secretKey = $secretKey;
//
//        if (!is_null($version)) {
//            self::setVersion($version);
//        }

//        $this
//            ->setUrl($this->url)
//            ->setHeaders([
//                'Content-Type' => 'text/xml;charset=utf-8',
//                'X-EBAY-SOA-REQUEST-DATA-FORMAT' => 'XML',
//                'X-EBAY-SOA-SECURITY-APPNAME' => $appId,
//                'X-EBAY-SOA-SERVICE-VERSION' => $this->version
//            ]);

//        $countryCode = (string) $countryCode;
//
//        if (!isset($this->_baseUriList[$countryCode])) {
//            throw new Exception\InvalidArgumentException("Unknown country code: $countryCode");
//        }
//
//        $this->_baseUri = $this->_baseUriList[$countryCode];
    }


    protected function _prepareOptions($query, array $options, array $defaultOptions)
    {
        //$options['appId'] = $this->appId;
        //$options['Service']        = 'AWSECommerceService';
        //$options['Operation']      = (string) $query;
        //$options['Version']        = self::getVersion();

        $options = [
            'url' => $this->endpoint,
            'header' => [
                'X-EBAY-SOA-OPERATION-NAME' => (string)$query,
                'X-EBAY-SOA-GLOBAL-ID' => static::$global_id
            ],
            'method' => $this->method,
//            'body' => json_encode([
//                'findItemsByKeywordsRequest' => [
//                    'xmlns' => $this->xmlns,
//                    'keywords' => $params[0]
//                ]
//            ]),
//            'response' => [
//                'valid_codes' => ['200', '203']
//            ]
        ];


        $options = array_merge($defaultOptions, $options);


        return $options;
    }



//    /**
//     * @param $name
//     * @param $body
//     * @return mixed
//     */
//    public function config($name, $body)
//    {
//        $config['header']['X-EBAY-SOA-OPERATION-NAME'] = $name;
//        $config['header']['X-EBAY-SOA-GLOBAL-ID'] = static::$global_id;
//
//        $config['url'] = $this->endpoint;
//        $config['method'] = $this->method;
//        $config['body'] = $body;
//
//        return $config;
//    }


public function __invoke($data)
{
    // TODO: Implement __invoke() method.
}


    public function call($name, $data)
    {
        if (!$this->api->hasApi($name)) {

            $this->api->set($name, function ($data) {
                return [
                    'url' => $this->url,
                    'header' => [
                        'Content-Type' => 'application/json',
                        'X-EBAY-SOA-REQUEST-DATA-FORMAT' => 'XML',
                        'X-EBAY-SOA-SECURITY-APPNAME' => $this->accessKey,
                        'X-EBAY-SOA-SERVICE-VERSION' => $this->version,
                        'X-EBAY-SOA-GLOBAL-ID' => $this->global,
                        'X-EBAY-SOA-OPERATION-NAME' => (string)$name
                    ],
                    'method' => $this->method,
                    'body' => json_encode($data),
                    'response' => [
                        'valid_codes' => ['200', '203'],
                        'format' => 'XML'
                    ]
                ];
            });
//            $this->api->set($name, function ($data) {
//
//                //$this->getApi()->setRequest($name, $this->xmlns)->setToken($this->token);
//
////                $client = $this->getHttpClient();
////                $client->setOptions([
////                    'timeout' => 60
////                ]);
////
////
            /*                $this->xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><{$name}Request />");*/
////                $this->xml->addAttribute('xmlns', $xmlns);
//
//
//
//
//                $data['header']['X-EBAY-SOA-OPERATION-NAME'] = $name;
//                $data['header']['X-EBAY-SOA-GLOBAL-ID'] = static::$global_id;
//
//                $config['url'] = $this->endpoint;
//                $config['method'] = $this->method;
//                $config['body'] = $body;
//
//
//
//
//                return $data;
//            });
        }

        $response = $this->api->{$name}($data);

        if (!$this->api->isSuccess()) {
            throw new \RuntimeException(
                "An error occurred sending request.
                 Status code: {$this->api->getStatus()}.
                 Error message: {$this->api->getError()}."
            );
        }

//        $response = new \SimpleXMLElement($response);
//
//        if (isset($response->version)) {
//            $this->version = (string)$response->version;
//        } elseif ($response->Version) {
//            $this->version = (string)$response->Version;
//        }
//
//        if (isset($response->build)) {
//            $this->build = (string)$response->build;
//        } elseif ($response->Build) {
//            $this->build = (string)$response->Build;
//        }
//
//        if (isset($response->timestamp)) {
//            $this->timestamp = (string)$response->timestamp;
//        } elseif ($response->Timestamp) {
//            $this->timestamp = (string)$response->Timestamp;
//        }

        return $response;
    }


    /**
     * @return string
     */
    public static function toXml($name, array $data)
    {
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><{$name} />");

        foreach ($data as $name => $value) {
            if (is_array($value)) {
                $xml = static::toXml($name, $value);
            } else {
                $xml->{$name} = $value;
            }
        }

        return $xml;
    }

    /**
     * @return string
     */
    public static function toJson(array $data)
    {
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><{$name} />");

        foreach ($data as $name => $value) {
            if (is_array($value)) {
                $xml = static::toXml($name, $value);
            } else {
                $xml->{$name} = $value;
            }
        }

        return $xml;
    }




//    /**
//     * @return string
//     */
//    public function getVersion()
//    {
//        return (string)$this->call('getVersion')->version;
//    }

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
    public function findItemsByKeywordsRequest($params)
    {
//        return new ResultSet($this->call('findItemsByKeywords', [
//            'aspectFilter' => $aspect_filter,
//            'itemFilter' => $item_filter,
//            'keywords' => $keywords,
//            'outputSelector' => $output_selector,
//            'affiliate' => $affiliate,
//            'buyerPostalCode' => $postal_code,
//            'paginationInput' => $pagination,
//            'sortOrder' => $sort_order
//        ]));


        $this->api->set(__METHOD__, function ($params) {
            return [
                'url' => $this->url,
                'header' => [
                    'Content-Type' => 'application/json',
                    'X-EBAY-SOA-REQUEST-DATA-FORMAT' => 'XML',
                    'X-EBAY-SOA-SECURITY-APPNAME' => $this->accessKey,
                    'X-EBAY-SOA-SERVICE-VERSION' => $this->version,
                    'X-EBAY-SOA-GLOBAL-ID' => $params[0]
                ],
                'method' => 'POST',
                'body' => json_encode([
                    'findItemsByKeywordsRequest' => [
                        'xmlns' => $this->xmlns,
                        'keywords' => $params[0]
                    ]
                ]),
                'response' => [
                    'valid_codes' => ['200', '203']
                ]
            ];

        });

    }

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

    public function getSearchKeywordsRecommendation($keywords)
    {
        return new ResultSet($this->call('getSearchKeywordsRecommendation', [
            'keywords' => $keywords
        ]));
    }


    public static function setVersion($version)
    {
        if (!preg_match('/\d{1,2}\.\d{1,2}\.\d{1,2}/', $version)) {
            throw new Exception\InvalidArgumentException("$version is an invalid API Version");
        }
        self::$version = $version;
    }

    public static function getVersion()
    {
        return self::$version;
    }
}
