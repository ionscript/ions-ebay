<?php

namespace Ions\Ebay;

/**
 * Class ResultSet
 * @package Ions\Ebay
 */
class ResultSet
{
    /**
     * @var \SimpleXMLElement $response
     */
    protected $response;

    /**
     * @var
     */
    private static $ack;

    /**
     * ResultSet constructor.
     * @param \SimpleXMLElement $response
     */
    public function __construct(\SimpleXMLElement $response)
    {
        $this->response = $response;
        static::$ack = (string)$this->response->ack ?:(string)$this->response->Ack;
    }

    /**
     * @return string
     */
    public function total()
    {
        return (string)$this->response->paginationOutput->totalEntries;
    }

    /**
     * @return array
     */
    public function error()
    {
        $error = [];

        if($this->response->errorMessage) {
            $error = [
                'code' => (string)$this->response->errorMessage->error->errorId,
                'message' => (string)$this->response->errorMessage->error
            ];
        } elseif($this->response->Errors) {
            $error = [
                'code' => (string)$this->response->Errors->ErrorCode,
                'message' => (string)$this->response->Errors->ShortMessage
            ];
        }

        return $error;
    }

    /**
     * @return string
     */
    public function pages()
    {
        return (string)$this->response->paginationOutput->totalPages;
    }

    /**
     * @return string
     */
    public function page()
    {
        if(isset($this->response->PageNumber)) {
            return (string)$this->response->PageNumber;
        }

        return (string)$this->response->paginationOutput->pageNumber;
    }

    /**
     * @return array
     */
    public function items()
    {
        $items = [];

        if(isset($this->response->searchResult->item)){
            foreach ($this->response->searchResult->item as $item){
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * @return array
     */
    public function listing()
    {
        $items = [];

        if(isset($this->response->Item)) {
            foreach ($this->response->Item as $item){
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * @return array
     */
    public function category()
    {
        $categories = [];

        if(isset($this->response->CategoryArray->Category)) {
            foreach ($this->response->CategoryArray->Category as $category) {
                $categories[] = $category;
            }
        }
        
        return $categories;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return static::$ack === 'Success';
    }

    /**
     * @return mixed
     */
    public function ack()
    {
        return static::$ack;
    }
}
