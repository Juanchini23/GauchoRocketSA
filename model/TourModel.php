<?php

class TourModel
{

    private $dataBase;

    public function __construct($dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function getTours($dia, $origen)
    {

       return $this->dataBase->getTours($dia,$origen);

    }
}