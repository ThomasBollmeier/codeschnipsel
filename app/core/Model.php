<?php
/*
   Copyright 2016 Thomas Bollmeier <entwickler@tbollmeier.de>

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

namespace tbollmeier\codeschnipsel\core;

abstract class Model
{
    const INDEX_NOT_IN_DB = -1;

    protected $tableName;
    protected $id;
    protected $row;
    protected $dbNames;

    public function __construct(int $id=-1)
    {
        $this->id = intval($id);
        $this->tableName = '';
        $this->row = [];
        $this->dbNames = [];
    }

    public function getId()
    {
        return $this->id;
    }

    protected function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    protected function setDbAlias($name, $dbName)
    {
        $this->dbNames[$name] = $dbName;
    }

    protected function setRowData($row)
    {
        $this->row = $row;
    }

    public function __get($name)
    {
        $dbName = isset($this->dbNames[$name]) ?
            $this->dbNames[$name] : $name;

        if (isset($this->row[$dbName])) {
            return $this->row[$dbName];
        } else {
            return null;
        }
    }

    public function __set($name, $value)
    {
        $dbName = isset($this->dbNames[$name]) ?
            $this->dbNames[$name] : $name;

        $this->row[$dbName] = $value;
    }

    function load(\PDO $dbConn)
    {
        if ($this->id == self::INDEX_NOT_IN_DB) {
            return;
        }

        $sql = 'SELECT * FROM '.$this->tableName.' WHERE id = :id';
        $stmt = $dbConn->prepare($sql);
        $stmt->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            $this->setRowData($row);
        }
        $stmt->closeCursor();
    }

    abstract function save(\PDO $dbConn);

}