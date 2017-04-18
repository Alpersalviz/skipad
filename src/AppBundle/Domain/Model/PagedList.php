<?php


namespace AppBundle\Domain\Model;


class PagedList
{

    public $Content;
    public $ListSize;
    public $Limit;
    public $SearchKey;

    public function __construct($content = null, $listSize = null, $limit = null , $searchKey = null)
    {
        $this->Content = $content;
        $this->Limit = $limit;
        $this->ListSize = $listSize;
        $this->SearchKey = $searchKey;
    }
}