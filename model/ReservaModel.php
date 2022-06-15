<?php

class ReservaModel
{

    private $dataBase;

    public function __construct($getDataBase)
    {
        $this->dataBase = $getDataBase;
    }

    public function guardarViajeFecha($idUser, $id, $fechaViaje)
    {
        $date = date_create($fechaViaje);
        $date = $date->format('Y-m-d');
        $this->dataBase->guardarVueloFecha($idUser, $id, $date);
    }

    public function getPlanificacion($id){
       return $this->dataBase->getPlani($id);
    }

    public function getUsuario($idUser){
        $this->dataBase->getUsu($idUser);
    }
}