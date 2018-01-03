<?php

namespace Ions\Ebay\Api;

use Ions\Ebay\Api;
use Ions\Ebay\ResultSet;

/**
 * Class Trading
 * @package Ions\Ebay\Api
 */
class Trading extends Api
{
    /**
     * @var string
     */
    public $version = '967';
    /**
     * @var string
     */
    public $url = 'https://api.';
    /**
     * @var string
     */
    public $endpoint = '/ws/api.dll';
    /**
     * @var string
     */
    public $xmlns = 'urn:ebay:apis:eBLBaseComponents';

    /**
     * @var
     */
    public static $site_id;

    /**
     * Trading constructor.
     * @param $url
     * @param $token
     */
    public function __construct($url, $token)
    {
        $this->token = $token;

        $this
            ->setUrl($this->url.$url)
            ->setHeaders([
                'Content-Type' => 'text/xml;charset=utf-8',
                'X-EBAY-API-COMPATIBILITY-LEVEL' => $this->version
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
        $config['header']['X-EBAY-API-SITEID'] = static::$site_id;

        $config['url'] = $this->endpoint;
        $config['method'] = $this->method;
        $config['body'] = $body;

        return $config;
    }

    /**
     * @param int $levelLimit
     * @return ResultSet
     */
    public function getCategories($levelLimit = 0)
    {
        $data = [
            'ErrorLanguage' => 'en_US',
            'WarningLevel' => 'High',
            'DetailLevel' => 'ReturnAll'
        ];

        if ($levelLimit) {
            $data['LevelLimit'] = $levelLimit;
        }

        $response = $this->call('GetCategories', $data);

        return new ResultSet($response);
    }
}
