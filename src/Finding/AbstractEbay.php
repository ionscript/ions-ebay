<?php

namespace Ions\Ebay;

use Ions\Http\Client as HttpClient;
use DateTime;


abstract class AbstractEbay
{
    protected $secretKey;
    protected $accessKey;
    protected $client;
    protected $date;
    protected $lastResponse;

    public function __construct($accessKey = null, $secretKey = null, HttpClient $client = null)
    {
        $this->setKeys($accessKey, $secretKey);
        $this->setClient(($client) ?: new HttpClient);
    }

    public function setKeys($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    public function setClient(HttpClient $client)
    {
        $this->client = $client;
        return $this;
    }

    public function getClient()
    {
        return $this->httpClient;
    }

    protected function getAccessKey()
    {
        if (is_null($this->accessKey)) {
            throw new Exception\InvalidArgumentException('AWS access key was not supplied');
        }

        return $this->accessKey;
    }

    protected function getSecretKey()
    {
        if (is_null($this->secretKey)) {
            throw new Exception\InvalidArgumentException('AWS secret key was not supplied');
        }

        return $this->secretKey;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setRequestDate(DateTime $date = null)
    {
        $this->date = $date;
    }

    public function getRequestDate()
    {
        if (!is_object($this->date)) {
            $date = new DateTime();
        } else {
            $date = $this->date;
        }

        return $date->format(DateTime::RFC1123);
    }
}
