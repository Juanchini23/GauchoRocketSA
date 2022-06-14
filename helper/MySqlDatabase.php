<?php

class MySqlDatabase
{

    private $host;
    private $user;
    private $pass;
    private $database;

    private $conn;

    public function __construct($host, $user, $pass, $database)
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;

        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function query($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function queryAltaUsuario($nombre, $apellido, $mail, $clave, $codigoViajero)
    {
        $sql = "INSERT INTO usuario(idRol, nombre, apellido, mail, clave, codigoViajero) values (?, ?, ?, ?, ?, ?);";
        $comando = $this->conn->prepare($sql);
        $dos = 2;
        $comando->bind_param("issssi", $dos, $nombre, $apellido, $mail, $clave, $codigoViajero);
        $comando->execute();
    }

    public function iniciarSesion($nombre, $clave)
    {
        $sql = "SELECT * FROM usuario WHERE nombre = ? AND clave = ?";
        $comando = $this->conn->prepare($sql);
        $comando->bind_param("ss", $nombre, $clave);
        $comando->execute();
        $resultado = $comando->get_result();
        return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    }

    public function actualizarNombre($nombre)
    {
        $sql = "UPDATE usuariologeado SET nombre=?;";
        $comando = $this->conn->prepare($sql);
        $comando->bind_param("s", $nombre);
        $comando->execute();
    }

    public function guardarVueloFecha($idUser, $id, $date){
        $sql = "INSERT INTO reserva(idUsuario, idPlanificacion, fecha) values (?, ?, ?);";
        $comando = $this->conn->prepare($sql);
        $comando->bind_param("iis", $idUser, $id, $date);
        $comando->execute();

    }

    public function getPlani($id){

    }

    public function getUsu($id){

    }

    private function connect()
    {
        $conn = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
        if (!$conn) {
            die('Connection failed: ' . mysqli_connect_error());
        }
        $this->conn = $conn;
    }

    private function disconnect()
    {
        mysqli_close($this->conn);
    }
}