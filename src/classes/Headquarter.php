<?php
/**
 * Created by PhpStorm.
 * User: dprinzensteiner
 * Date: 22.01.2018
 * Time: 21:31
 */

class Headquarter extends Building {

    /**
     * Headquarter constructor.
     * @param string $id
     */
    public function __construct($id = "") {
        if(!empty($id)) {
            $this->id = $id;
        }
        $this->databaseTable = "headquarters";
    }
}