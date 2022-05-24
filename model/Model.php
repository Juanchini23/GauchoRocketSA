<?php

class Model
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function consultar($consulta)
    {
        return $this->database->query($consulta);
    }
}
