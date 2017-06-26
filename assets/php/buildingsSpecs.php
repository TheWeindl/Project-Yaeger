<?php
//File containing all different buildings and their different levels and values for these levels
//All data should be contained in arrays to make using the information easy

//=====================================================================================================================
// Wood Factory
//=====================================================================================================================
//Production of the factory per minute
$woodFactoryProduction = array(
    1 => 1,
    2 => 3,
    3 => 5,
    4 => 8,
    5 => 10
);

//Costs of the upgrades of the factory
//First index is the level of the factory currently set
$woodFactoryCost = array(
    1 => array( "wood" => 1000,  "stone" => 1000,  "metal" => 0),
    2 => array( "wood" => 3000,  "stone" => 3500,  "metal" => 0),
    3 => array( "wood" => 6000,  "stone" => 7000,  "metal" => 0),
    4 => array( "wood" => 13000, "stone" => 11000, "metal" => 2000),
    5 => array( "wood" => 20000, "stone" => 19000, "metal" => 4000),
);

//=====================================================================================================================
// Metal Factory
//=====================================================================================================================
//Production of the factory per minute
$metalFactoryProduction = array(
    1 => 1,
    2 => 3,
    3 => 5,
    4 => 8,
    5 => 10
);

//Costs of the upgrades of the factory
//First index is the level of the factory currently set
$metalFactoryCost = array(
    1 => array( "wood" => 1000,  "stone" => 1000,  "metal" => 0),
    2 => array( "wood" => 3000,  "stone" => 3500,  "metal" => 0),
    3 => array( "wood" => 6000,  "stone" => 7000,  "metal" => 0),
    4 => array( "wood" => 13000, "stone" => 11000, "metal" => 2000),
    5 => array( "wood" => 20000, "stone" => 19000, "metal" => 4000),
);

//=====================================================================================================================
// Stone Factory
//=====================================================================================================================
//Production of the factory per minute
$stoneFactoryProduction = array(
    1 => 1,
    2 => 3,
    3 => 5,
    4 => 8,
    5 => 10
);

//Costs of the upgrades of the factory
//First index is the level of the factory currently set
$stoneFactoryCost = array(
    1 => array( "wood" => 1000,  "stone" => 1000,  "metal" => 0),
    2 => array( "wood" => 3000,  "stone" => 3500,  "metal" => 0),
    3 => array( "wood" => 6000,  "stone" => 7000,  "metal" => 0),
    4 => array( "wood" => 13000, "stone" => 11000, "metal" => 2000),
    5 => array( "wood" => 20000, "stone" => 19000, "metal" => 4000),
);

//=====================================================================================================================
// Headquarter
//=====================================================================================================================
//Costs of the upgrades of the factory
//First index is the level of the building currently set
$headquarterCost = array(
    1 => array( "wood" => 1000,  "stone" => 1000,  "metal" => 0),
    2 => array( "wood" => 3000,  "stone" => 3500,  "metal" => 0),
    3 => array( "wood" => 6000,  "stone" => 7000,  "metal" => 0),
    4 => array( "wood" => 13000, "stone" => 11000, "metal" => 2000),
    5 => array( "wood" => 20000, "stone" => 19000, "metal" => 4000),
);

//=====================================================================================================================
// Farm
//=====================================================================================================================
//Production of the factory per minute
$farmProduction = array(
    1 => 1,
    2 => 3,
    3 => 5,
    4 => 8,
    5 => 10
);

//Costs of the upgrades of the factory
//First index is the level of the factory currently set
$farmCost = array(
    1 => array( "wood" => 1000,  "stone" => 1000,  "metal" => 0),
    2 => array( "wood" => 3000,  "stone" => 3500,  "metal" => 0),
    3 => array( "wood" => 6000,  "stone" => 7000,  "metal" => 0),
    4 => array( "wood" => 13000, "stone" => 11000, "metal" => 0),
    5 => array( "wood" => 20000, "stone" => 19000, "metal" => 0),
);

//=====================================================================================================================
// Storage
//=====================================================================================================================
//Production of the factory per minute
$storageCapacity = array(
    1 => 5000,
    2 => 7000,
    3 => 10000,
    4 => 15000,
    5 => 20000
);

//Costs of the upgrades of the factory
//First index is the level of the factory currently set
$storageCost = array(
    1 => array( "wood" => 4000,  "stone" => 4000,  "metal" => 0),
    2 => array( "wood" => 5000,  "stone" => 5000,  "metal" => 0),
    3 => array( "wood" => 6000,  "stone" => 7000,  "metal" => 0),
    4 => array( "wood" => 13000, "stone" => 11000, "metal" => 0),
    5 => array( "wood" => 20000, "stone" => 19000, "metal" => 0),
);
?>