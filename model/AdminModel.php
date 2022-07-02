<?php

class AdminModel
{
    private $dataBase;

    public function __construct($dataBase)
    {

        $this->dataBase = $dataBase;
    }

    public function getTOcupacionPorviaje(){
        return $this->dataBase->query();
    }

}