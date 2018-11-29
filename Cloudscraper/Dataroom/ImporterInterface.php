<?php
namespace Library\Cloudscraper\Dataroom;

interface ImporterInterface
{
    public function setData($data);
    
    public function import();
    
}
