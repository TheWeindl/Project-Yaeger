<?php

/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 22.01.2018
 * Time: 21:50
 */
class InformationManager
{
    private $buildings;

    /**
     * InformationManager constructor.
     */
    public function __construct(){
        $stats = $this->loadJSON();
        $this->buildings = $stats->buildings;
    }

    /**
     * @param Building $building
     * @return mixed
     * Gets all information of the given building
     */
    public function getAllInformation($building) {
        return $this->buildings->$building;
    }

    /**
     * @param string $filename
     * @return mixed
     * Loads the stats from the stats.json file
     */
    private function loadJSON($filename = "../config/stats.json") {
        $json = file_get_contents($filename);
        return json_decode($json);
    }


}