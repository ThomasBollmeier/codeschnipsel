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

namespace tbollmeier\codeschnipsel\model;

use tbollmeier\codeschnipsel\core\Model;


class Language extends Model
{
    public static function getAll(\PDO $dbConn)
    {
        $languages = [];

        $sql = "SELECT * FROM languages";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();

        $row = $stmt->fetch();
        while ($row) {
            $language = new Language('', $row['id']);
            $language->setRowData($row);
            $languages[] = $language;
            $row = $stmt->fetch();
        }

        $stmt->closeCursor();

        return $languages;
    }

    public function __construct($name='', $id=-1)
    {
        parent::__construct($id);

        $this->setTableName('languages');
        $this->name = $name;
    }

    public function save(\PDO $dbConn)
    {
        if ($this->id == Model::INDEX_NOT_IN_DB) {

            $sql = <<<SQL
INSERT INTO languages
  (name)
VALUES
  (:name)
SQL;
            $stmnt = $dbConn->prepare($sql);
            $stmnt->bindParam(':name', $this->name);

        } else {

            $sql = <<<SQL
UPDATE languages SET
  name = :name
WHERE
  id = :id
SQL;
            $stmnt = $dbConn->prepare($sql);
            $stmnt->bindParam(':name', $this->name);
            $stmnt->bindParam(':id', $this->id, \PDO::PARAM_INT);

        }

        $stmnt->execute();

        if ($this->id == Model::INDEX_NOT_IN_DB) {
            $this->id = $dbConn->lastInsertId();
        }

    }

}