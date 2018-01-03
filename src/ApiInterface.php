<?php

namespace Ions\Ebay;

/**
 * Interface ApiInterface
 * @package Ions\Ebay
 */
interface ApiInterface
{
    /**
     * @param $name
     * @param $body
     * @return mixed
     */
    public function config($name, $body);
}
