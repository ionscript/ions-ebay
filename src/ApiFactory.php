<?php

namespace Ions\Ebay;

use Ions\Http\Client\Api;

/**
 * Class ApiFactory
 * @package Ions\Ebay
 */
class ApiFactory extends Api
{
    /**
     * @var
     */
    private $xml;

    /**
     * @param $name
     * @return bool
     */
    public function hasApi($name)
    {
        return array_key_exists($name, $this->api);
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        if ($token) {
            $this->getXml()->RequesterCredentials->eBayAuthToken = $token;
        }

        return $this;
    }

    /**
     * @param $xml
     * @param $xmlns
     * @return $this
     */
    public function setRequest($xml, $xmlns)
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
    public function addXml(array $xml, $key = null)
    {
        foreach ($xml as $name => $value) {
            if (is_array($value)) {
                $this->addXml($value, $name);
            } else {
                if ($key) {
                    $this->getXml()->{$key}->{$name} = $value;
                } else {
                    $this->getXml()->{$name} = $value;
                }
            }
        }

        return $this;
    }

    /**
     * @param array $filters
     */
    public function itemFilter(array $filters)
    {
        if ($filters) {
            foreach ($filters as $name => $values) {
                $itemFilter = $this->getXml()->addChild('itemFilter');
                $itemFilter->name = $name;

                foreach ((array)$values as $value) {
                    $itemFilter->addChild('value', $value);
                }
            }
        }
    }

    /**
     * @param array $filters
     */
    public function aspectFilter(array $filters)
    {
        if ($filters) {
            foreach ($filters as $name => $value) {
                $aspectFilter = $this->getXml()->addChild('aspectFilter');
                $aspectFilter->aspectName = $name;
                $aspectFilter->aspectValueName = $value;
            }
        }
    }

    /**
     * @param $pagination
     */
    public function paginationInput($pagination)
    {
        $this->addXml([
            'paginationInput' => $pagination
        ]);
    }

    /**
     * @param $sortOrder
     */
    public function sortOrder($sortOrder)
    {
        if ($sortOrder) {
            $this->getXml()->sortOrder = $sortOrder;
        }
    }

    /**
     * @param $outputSelector
     */
    public function outputSelector($outputSelector)
    {
        if ($outputSelector) {
            $this->getXml()->outputSelector = $outputSelector;
        }
    }

    /**
     * @param $keywords
     */
    public function keywords($keywords)
    {
        if ($keywords) {
            $this->getXml()->keywords = $keywords;
        }
    }

    /**
     * @param $categoryId
     */
    public function categoryId($categoryId)
    {
        if ($categoryId) {
            $this->getXml()->categoryId = $categoryId;
        }
    }

    /**
     * @param $categoryId
     */
    public function productCategoryId($categoryId)
    {
        if ($categoryId) {
            $this->getXml()->CategoryID = $categoryId;
        }
    }

    /**
     * @param $storeName
     */
    public function storeName($storeName)
    {
        if ($storeName) {
            $this->getXml()->storeName = $storeName;
        }
    }

    /**
     * @param $warningLevel
     */
    public function warningLevel($warningLevel)
    {
        $this->getXml()->WarningLevel = $warningLevel;
    }

    /**
     * @param $viewAllNode
     */
    public function viewAllNode($viewAllNode)
    {
        if ($viewAllNode) {
            $this->getXml()->ViewAllNode = $viewAllNode;
        }

    }

    /**
     * @param $errorLanguage
     */
    public function errorLanguage($errorLanguage)
    {
        $this->getXml()->ErrorLanguage = $errorLanguage;
    }

    /**
     * @param $detailLevel
     */
    public function detailLevel($detailLevel)
    {
        if ($detailLevel) {
            $this->getXml()->DetailLevel = $detailLevel;
        }
    }

    /**
     * @param $levelLimit
     */
    public function levelLimit($levelLimit)
    {
        if ($levelLimit) {
            $this->getXml()->LevelLimit = $levelLimit;
        }
    }

    /**
     * @param $keywords
     */
    public function queryKeywords($keywords)
    {
        if ($keywords) {
            $this->getXml()->QueryKeywords = $keywords;
        }

    }

    /**
     * @param $limit
     */
    public function maxEntries($limit)
    {
        if ($limit) {
            $this->getXml()->MaxEntries = $limit;
        }
    }

    /**
     * @param $page
     */
    public function pageNumber($page)
    {
        if ($page) {
            $this->getXml()->PageNumber = $page;
        }

    }

    /**
     * @param $available
     */
    public function availableItemsOnly($available)
    {
        if ($available) {
            $this->getXml()->AvailableItemsOnly = $available;
        }
    }

    /**
     * @param array $productId
     */
    public function productId(array $productId)
    {
        if ($productId) {
            $this->getXml()->productId = key($productId);

            foreach (array_shift($productId) as $name => $value) {
                $this->getXml()->productId->addAttribute($name, $value);
            }
        }
    }

    /**
     * @param array $ids
     */
    public function itemId(array $ids)
    {
        if ($ids) {
            foreach ($ids as $id) {
                $this->getXml()->addChild('ItemID', $id);
            }
        }
    }

    /**
     * @param array $productId
     */
    public function productProductId(array $productId)
    {
        if ($productId) {
            $this->getXml()->ProductID = key($productId);

            foreach (array_shift($productId) as $name => $value) {
                $this->getXml()->ProductID->addAttribute($name, $value);
            }
        }
    }

    /**
     * @param $domain
     */
    public function domainName($domain)
    {
        if ($domain) {
            $this->getXml()->DomainName = $domain;
        }
    }

    /**
     * @param $selector
     */
    public function includeSelector($selector)
    {
        if ($selector) {
            $this->getXml()->IncludeSelector = $selector;
        }
    }

    /**
     * @param $search
     */
    public function descriptionSearch($search)
    {
        if ($search) {
            $this->getXml()->descriptionSearch = $search;
        }
    }

    /**
     * @param $code
     */
    public function buyerPostalCode($code)
    {
        if ($code) {
            $this->getXml()->buyerPostalCode = $code;
        }
    }
}
