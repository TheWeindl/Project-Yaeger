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
    public function __construct()
    {
        $stats = $this->loadJSON();
        $this->buildings = $stats->buildings;
    }

    private function loadJSON($filename = "../config/stats.json") {
        $json = file_get_contents($filename);
        return json_decode($json);
    }


}