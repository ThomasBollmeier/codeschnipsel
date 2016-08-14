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


class Snippet extends Model
{

	public static function getAllOf(\PDO $dbConn, User $author, $publicOnly=true)
	{
	    $snippets = [];

	    $sql = <<<SQL
SELECT * 
FROM 
  snippets
WHERE
  author_id = :author_id
ORDER BY
  last_changed_at DESC 
SQL;
        $stmt = $dbConn->prepare($sql);
        $stmt->execute([':author_id' => $author->getId()]);

        $row = $stmt->fetch();
        while ($row) {
            $snippet = new Snippet($row['id']);
            $snippet->setRowData($row);
            $snippets[] = $snippet;
            $row = $stmt->fetch();
        }

        $stmt->closeCursor();

		return $snippets;
	}
	
	public function __construct($id=-1)
	{
		parent::__construct($id);

        $this->setTableName('snippets');
        $this->setDbAlias('authorId', 'author_id');
        $this->setDbAlias('languageId', 'language_id');
	}

	public function getLanguage(\PDO $dbConn)
    {
        $langId = intval($this->languageId);
        if (!empty($langId) && $langId != Model::INDEX_NOT_IN_DB) {
            $lang = new Language('', $langId);
            $lang->load($dbConn);
            return $lang->name;
        } else {
            return "";
        }
    }

    public function setLanguage(\PDO $dbConn, $languageName)
    {
        $languages = Language::getAll($dbConn);
        foreach ($languages as $language) {
            if ($language->name == $languageName) {
                $this->languageId = $language->getId();
                return;
            }
        }
        // Unknown language
        $this->languageId = Model::INDEX_NOT_IN_DB;

    }

    public function save(\PDO $dbConn)
    {
        if ($this->id == Model::INDEX_NOT_IN_DB) {

            $sql = <<<SQL
INSERT INTO snippets 
  (title, author_id, code, language_id)
VALUES
  (:title, :author_id, :code, :language_id)
SQL;

            $stmnt = $dbConn->prepare($sql);
            $stmnt->bindParam(':title', $this->title);
            $stmnt->bindParam(':author_id', $this->authorId, \PDO::PARAM_INT);
            $stmnt->bindParam(':code', $this->code);
            $stmnt->bindParam(':language_id', $this->languageId, \PDO::PARAM_INT);

        } else {

            $sql = <<<SQL
UPDATE snippets SET
  title = :title,
  code = :code,
  language_id = :language_id
WHERE
  id = :id
SQL;
            $stmnt = $dbConn->prepare($sql);
            $stmnt->bindParam(':title', $this->title);
            $stmnt->bindParam(':code', $this->code);
            $stmnt->bindParam(':language_id', $this->languageId, \PDO::PARAM_INT);
            $stmnt->bindParam(':id', $this->id, \PDO::PARAM_INT);

        }

        $stmnt->execute();

        if ($this->id == Model::INDEX_NOT_IN_DB) {
            $this->id = $dbConn->lastInsertId();
        }

    }

}
