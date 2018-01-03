<?php

namespace Ions\Ebay;

/**
 * Class Api
 * @package Ions\Ebay
 */
abstract class Api implements ApiInterface
{
    /**
     * @var string
     */
    public $token;

    /**
     * @var
     */
    private $api;

    /**
     * @var string
     */
    public $endpoint;

    /**
     * @var string
     */
    public $version;

    /**
     * @var string
     */
    public $method = 'POST';

    /**
     * @var string
     */
    public $xmlns;

    /**
     * @var
     */
    public $timestamp;

    /**
     * @var
     */
    public $ack;

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->getApi()->setUrl($url);

        return $this;
    }

    /**
     * @param $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->getApi()->setHeaders($headers);

        return $this;
    }

    /**
     * @param $name
     * @param array $data
     * @return \SimpleXMLElement
     * @throws \RuntimeException
     */
    public function call($name, array $data = [])
    {
        if (!$this->hasApi($name)) {
            $this->setApi($name);
        }

        $response = $this->getApi()->{$name}($data);

        if (!$this->getApi()->isSuccess()) {
            throw new \RuntimeException(
                "An error occurred sending request.
                 Status code: {$this->getApi()->getStatusCode()}.
                 Error message: {$this->getApi()->getErrorMsg()}."
            );
        }

        $response = new \SimpleXMLElement($response);

        if (isset($response->version)) {
            $this->version = (string)$response->version;
        } elseif ($response->Version) {
            $this->version = (string)$response->Version;
        }

        if (isset($response->build)) {
            $response->build = (string)$response->build;
        } elseif ($response->Build) {
            $response->build = (string)$response->Build;
        }

        if (isset($response->timestamp)) {
            $this->timestamp = (string)$response->timestamp;
        } elseif ($response->Timestamp) {
            $this->timestamp = (string)$response->Timestamp;
        }

        if (isset($response->ack)) {
            $this->ack = (string)$response->ack;
        } elseif ($response->Ack) {
            $this->ack = (string)$response->Ack;
        }

        return $response;
    }

    /**
     * @return ApiFactory
     */
    public function getApi()
    {
        return $this->api ?: $this->api = new ApiFactory();
    }

    /**
     * @param $name
     * @return $this
     */
    public function setApi($name)
    {
        $this->getApi()->setApi($name, function ($params) use ($name) {

            $this->getApi()->setRequest($name, $this->xmlns)->setToken($this->token);

            if (is_array($params[0])) {
                foreach ($params[0] as $key => $value) {
                    $this->getApi()->{$key}($value);
                }
            }

           $config =  $this->config($name, $this
                ->getApi()
                ->getXml()
                ->asXML()
            );

            return $config;
        });

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasApi($name)
    {
        return $this->getApi()->hasApi($name);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->ack === 'Success';
    }
}
