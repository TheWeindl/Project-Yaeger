<?php
/**
 * Created by PhpStorm.
 * User: dprinzensteiner
 * Date: 22.01.2018
 * Time: 20:43
 */

abstract class Building
{
    protected $name;
    protected $databaseTable;
    protected $id;
    protected $level;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDatabaseTable()
    {
        return $this->databaseTable;
    }

    /**
     * @param mixed $databaseName
     */
    public function setDatabaseTable($databaseTable)
    {
        $this->databaseName = $databaseTable;
    }

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