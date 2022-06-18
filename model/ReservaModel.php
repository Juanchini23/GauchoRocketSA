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

    public function getPlanificacion($id)
    {
        return $this->dataBase->getPlani($id);
    }

    public function getUsuario($idUser)
    {
        $this->dataBase->getUsu($idUser);
    }

    public function getDatosModelo($id)
    {
        return $this->dataBase->getDatosModelo($id);
    }

    public function getDatosLlegada($id, $fehaViaje)
    {
        $miFecha = date('Y-m-d H:i:s');
        $diaLlegada = 1;
        $horaLlegada = strtotime('+5 hour', strtotime($miFecha));
        return array(["diaLlegada" => $diaLlegada, "horaLLegada" => $horaLlegada]);
    }
}