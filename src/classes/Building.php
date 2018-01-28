<?php

include_once "BaseClass.php";

/**
 * Created by PhpStorm.
 * User: dprinzensteiner
 * Date: 22.01.2018
 * Time: 20:43
 */

abstract class Building extends BaseClass
{
    protected $id;
    protected $level;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }
}