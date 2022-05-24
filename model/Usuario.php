<?php

class Usuario extends Model
{

    private $id;
    
    public function __construct(
        private string $nombre,
        private string $apellido,
        private string $clave,
        private string $mail
    ) {

    }

    public function save(){
        $hash = $this->getHashedPassword($this->clave);
        $query = $this->prepare
    }

    private function getHashedPassword($password){
        return md5($password);
    }
}
