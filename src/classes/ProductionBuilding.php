<?php

/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 22.01.2018
 * Time: 21:42
 */
abstract class ProductionBuilding extends Building {

    /**
     * @return mixed
     * Returns the current production rate of the building
     */
    public function getCurrentProduction() {
        $informationManager = new InformationManager();
        $buildingInfo = $informationManager->getAllInformation(static::BUILDING_NAME);

        $level = "1";
        return $buildingInfo->$level->production;
    }

    public function updateStats() {
        //TODO
    }

    /**
     * @return mixed
     * Returns the upgrade costs of the building (json object)
     */
    public function getUpgradeCosts(){
        $informationManager = new InformationManager();
        $buildingInfo = $informationManager->getAllInformation(static::BUILDING_NAME);
        return $buildingInfo->cost;
    }
}