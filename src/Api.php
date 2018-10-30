<?php

namespace Ions\Ebay;

/**
 * Class Api
 * @package Ions\Ebay
 */
abstract class Api extends \Ions\Http\Client\Api implements ApiInterface
{
//    /**
//     * @var string
//     */
//    public $token;
//
//    /**
//     * @var
//     */
//    private $api;
//
//    /**
//     * @var string
//     */
//    public $endpoint;
//
//    /**
//     * @var string
//     */
//    public $version;
//
//    /**
//     * @var string
//     */
//    public $method = 'POST';
//
//    /**
//     * @var string
//     */
//    public $xmlns;

    /**
     * @var
     */
    public $timestamp;

    /**
     * @var
     */
    public $build;

    /**
     * @param $name
     * @param array $data
     * @return \SimpleXMLElement
     * @throws \RuntimeException
     */
    public function call($name, array $data = [])
    {
//        if (!isset($this->{$name})) {
//            $this->set($name, $data);
//        }

        $response = $this->{$name}($data);

        if (!$this->isSuccess()) {
            throw new \RuntimeException(
                "An error occurred sending request.
                 Status code: {$this->getStatusCode()}.
                 Error message: {$this->getErrorMsg()}."
            );
        }

        $response = new \SimpleXMLElement($response);

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


//    /**
//     * @return ApiFactory
//     */
//    public function getApi()
//    {
//        return $this->api ?: $this->api = new ApiFactory();
//    }

//    /**
//     * @param $name
//     * @return $this
//     */
//    public function setApis($name)
//    {
//        $this->setApi($name, function ($params) use ($name) {
//
//            $this->getApi()->setRequest($name, $this->xmlns)->setToken($this->token);
//
//            if (is_array($params[0])) {
//                foreach ($params[0] as $key => $value) {
//                    $this->getApi()->{$key}($value);
//                }
//            }
//
//           $config =  $this->config($name, $this
//                ->getApi()
//                ->getXml()
//                ->asXML()
//            );
//
//            return $config;
//        });
//
//        return $this;
//    }










    /**
     * @var
     */
//    private $xml;

    /**
     * @param $name
     * @return bool
     */
//    public function hasApi($name)
//    {
//        return array_key_exists($name, $this->api);
//    }

    /**
     * @return \SimpleXMLElement
     */
//    public function getXml()
//    {
//        return $this->xml;
//    }

    /**
     * @param $token
     * @return $this
     */
//    public function setToken($token)
//    {
//        if ($token) {
//            $this->xml->RequesterCredentials->eBayAuthToken = $token;
//        }
//
//        return $this;
//    }

    /**
     * @param $xml
     * @param $xmlns
     * @return $this
     */
    public function getXmlRequest($xml, $xmlns)
    {
        $client = $this->getHttpClient();
        $client->setOptions([
            'timeout' => 60
        ]);

        if ($xml instanceof \SimpleXMLElement) {
            $this->xml = $xml;
        } else {
            $this->xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><{$xml}Request />");
            $this->xml->addAttribute('xmlns', $xmlns);
        }

        return $this;
    }

    /**
     * @param array $xml
     * @param null $key
     * @return $this
     */
    public function toXml(array $data, $key = null)
    {
        foreach ($data as $tag => $value) {
            if (is_array($value)) {
                $this->toXml($value, $tag);
            } else {
                if ($key) {
                    $this->xml->{$key}->{$tag} = $value;
                } else {
                    $this->xml->{$tag} = $value;
                }
            }
        }

        return $this;
    }
}
