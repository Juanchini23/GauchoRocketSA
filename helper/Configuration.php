<?php
include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
require_once('helper/MustachePrinter.php');
include_once('controller/SongsController.php');
include_once('controller/ToursController.php');
include_once('controller/HomeController.php');
include_once('model/SongModel.php');
include_once('model/HomeModel.php');
include_once('model/TourModel.php');
include_once("model/HomeModel.php");
require_once('third-party/mustache/src/Mustache/Autoloader.php');

class Configuration {
    public function getSongsController() {
        return new SongsController($this->getSongModel(), $this->getPrinter());
    }

    public function getToursController() {
        return new ToursController($this->getTourModel(), $this->getPrinter());
    }

    public function getHomeController() {
        return new HomeController($this->getHomeModel(), $this->getPrinter());
    }

    private function getSongModel(): SongModel {
        return new SongModel($this->getDatabase());
    }

    private function getTourModel() {
        return new TourModel($this->getDatabase());
    }

    private function getHomeModel() {
        return new HomeModel($this->getDatabase());
    }

    private function getDataBase()
    {
        $config = parse_ini_file('config.ini');
        return new MySqlDatabase($config["host"], $config["usuario"], $config["clave"], $config["base"]);
    }

    private function getPrinter() {
        return new MustachePrinter("view");
    }

    public function getRouter() {
        return new Router($this, "getHomeController", "execute");
    }
}