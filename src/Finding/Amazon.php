<?php

namespace Ions\Ebay;

use DOMDocument;
use DOMXPath;

class Amazon
{
    public $appId;

    protected $secretKey = null;

    protected static $version = '2011-08-01';

//    protected $_baseUri = null;
//
//    protected $_baseUriList = array('US' => 'http://webservices.amazon.com',
//                                    'UK' => 'http://webservices.amazon.co.uk',
//                                    'DE' => 'http://webservices.amazon.de',
//                                    'JP' => 'http://webservices.amazon.co.jp',
//                                    'FR' => 'http://webservices.amazon.fr',
//                                    'CA' => 'http://webservices.amazon.ca');

    protected $_rest = null;


    public function __construct($appId, $countryCode = 'US', $secretKey = null)
    {
        $this->appId = (string) $appId;
        $this->secretKey = $secretKey;

        if (!is_null($version)) {
            self::setVersion($version);
        }

        $countryCode = (string) $countryCode;
        if (!isset($this->_baseUriList[$countryCode])) {
            throw new Exception\InvalidArgumentException("Unknown country code: $countryCode");
        }

        $this->_baseUri = $this->_baseUriList[$countryCode];
    }

    public function itemSearch(array $options)
    {
        $client = $this->getRestClient();
        $client->setUri($this->_baseUri);

        $defaultOptions = array('ResponseGroup' => 'Small');
        $options = $this->_prepareOptions('ItemSearch', $options, $defaultOptions);
        $client->getHttpClient()->resetParameters();
        $response = $client->restGet('/onca/xml', $options);

        if ($response->isClientError()) {
            throw new Exception\RuntimeException('An error occurred sending request. Status code: '
                                           . $response->getStatusCode());
        }

        $dom = new DOMDocument();
        $dom->loadXML($response->getBody());
        self::_checkErrors($dom);

        return new ResultSet($dom);
    }

    public function itemLookup($asin, array $options = array())
    {
        $client = $this->getRestClient();
        $client->setUri($this->_baseUri);
        $client->getHttpClient()->resetParameters();

        $defaultOptions = array('ResponseGroup' => 'Small');
        $options['ItemId'] = (string) $asin;
        $options = $this->_prepareOptions('ItemLookup', $options, $defaultOptions);
        $response = $client->restGet('/onca/xml', $options);

        if ($response->isClientError()) {
            throw new Exception\RuntimeException(
                'An error occurred sending request. Status code: ' . $response->getStatusCode()
            );
        }

        $dom = new DOMDocument();
        $dom->loadXML($response->getBody());
        self::_checkErrors($dom);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('az', 'http://webservices.amazon.com/AWSECommerceService/' . self::getVersion());
        $items = $xpath->query('//az:Items/az:Item');

        if ($items->length == 1) {
            return new Item($items->item(0));
        }

        return new ResultSet($dom);
    }

    public function getRestClient()
    {
        if ($this->rest === null) {
            $this->rest = new RestClient();
        }
        return $this->_rest;
    }

    public function setRestClient(RestClient $client)
    {
        $this->rest = $client;
        return $this;
    }


    protected function _prepareOptions($query, array $options, array $defaultOptions)
    {
        $options['AWSAccessKeyId'] = $this->appId;
        $options['Service']        = 'AWSECommerceService';
        $options['Operation']      = (string) $query;
        $options['Version']        = self::getVersion();

        // de-canonicalize out sort key
        if (isset($options['ResponseGroup'])) {
            $responseGroup = explode(',', $options['ResponseGroup']);

            if (!in_array('Request', $responseGroup)) {
                $responseGroup[] = 'Request';
                $options['ResponseGroup'] = implode(',', $responseGroup);
            }
        }

        $options = array_merge($defaultOptions, $options);

        if ($this->_secretKey !== null) {
            $options['Timestamp'] = gmdate("Y-m-d\TH:i:s\Z");
            ksort($options);
            $options['Signature'] = self::computeSignature($this->_baseUri, $this->_secretKey, $options);
        }

        return $options;
    }

    protected static function _checkErrors(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('az', 'http://webservices.amazon.com/AWSECommerceService/' . self::getVersion());

        if ($xpath->query('//az:Error')->length >= 1) {
            $code = $xpath->query('//az:Error/az:Code/text()')->item(0)->data;
            $message = $xpath->query('//az:Error/az:Message/text()')->item(0)->data;

            switch ($code) {
                case 'AWS.ECommerceService.NoExactMatches':
                    break;
                default:
                    throw new Exception\RuntimeException("$message ($code)");
            }
        }
    }

    public static function setVersion($version)
    {
        if (!preg_match('/\d{4}-\d{2}-\d{2}/', $version)) {
            throw new Exception\InvalidArgumentException("$version is an invalid API Version");
        }
        self::$version = $version;
    }


    public static function getVersion()
    {
        return self::$version;
    }
}
