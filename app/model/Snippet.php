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
        $this->setDbField('title');
        $this->setDbField(
            'authorId',
            [
                'dbAlias' => 'author_id',
                'pdoType' => \PDO::PARAM_INT
            ]);
        $this->setDbField('code');
        $this->setDbField(
            'languageId',
            [
                'dbAlias' => 'language_id',
                'pdoType' => \PDO::PARAM_INT
            ]);
        $this->setDbField(
            'lastChangedAt',
            [
                'dbAlias' => 'last_changed_at'
            ]);
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
        $this->languageId = null;

    }

}
