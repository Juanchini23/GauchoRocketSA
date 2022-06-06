<?php
include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
require_once('helper/MustachePrinter.php');

include_once('controller/LoginController.php');
include_once('controller/HomeController.php');
include_once('controller/LogoutController.php');
include_once('controller/RegistrarController.php');
include_once('controller/OrbitalController.php');
include_once('controller/TourController.php');

include_once('model/RegistrarModel.php');
include_once('model/LoginModel.php');
include_once('model/HomeModel.php');
include_once('model/HomeModel.php');
include_once('model/OrbitalModel.php');
include_once('model/TourModel.php');

require_once('third-party/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    public function getHomeController()
    {
        return new HomeController($this->getHomeModel(), $this->getPrinter());
    }


    public function getLoginController()
    {
        return new LoginController($this->getLoginModel(), $this->getPrinter());
    }

    public function getLogoutController()
    {
        return new LogoutController($this->getPrinter());
    }

    public function getRegistrarController()
    {
        return new RegistrarController($this->getRegistrarModel(), $this->getLoginModel(), $this->getPrinter());
    }

    public function getOrbitalController()
    {
        return new OrbitalController($this->getOrbitalModel(), $this->getPrinter());
    }

    public function getTourController()
    {
        return new TourController($this->getTourModel(), $this->getPrinter());
    }

    private function getRegistrarModel()
    {
        return new RegistrarModel($this->getDatabase());
    }

    private function getLoginModel()
    {
        return new LoginModel($this->getDatabase());
    }

    private function getHomeModel()
    {
        return new HomeModel($this->getDatabase());
    }

    private function getOrbitalModel()
    {
        return new OrbitalModel($this->getDataBase());
    }

    private function getTourModel()
    {
        return new TourModel($this->getDataBase());
    }

    private function getDataBase()
    {
        $config = parse_ini_file('config.ini');
        return new MySqlDatabase($config["host"], $config["usuario"], $config["clave"], $config["base"]);
    }

    private function getPrinter()
    {
        return new MustachePrinter("view");
    }

    public function getRouter()
    {
        return new Router($this, "getHomeController", "execute");
    }
}