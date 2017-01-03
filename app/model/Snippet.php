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

use tbollmeier\webappfound\db\ActiveRecord;


class Snippet extends ActiveRecord
{

	public static function findByUser(User $author)
	{
        $snippets = Snippet::query([
            'filter' => 'author_id = :author_id',
            'orderBy'=> 'last_changed_at DESC',
            'params' => [':author_id' => $author->getId()]
            ]);

        return $snippets;
	}

	public static function getPublicByTag($tag)
    {
        $sql = <<<SQL
SELECT 
  snippets.id,
  snippets.title,
  snippets.author_id,
  snippets.code,
  snippets.language_id,
  snippets.is_public,
  snippets.last_changed_at
  FROM snippets
  JOIN snippets_tags AS link
  ON link.snippet_id = snippets.id
  JOIN tags
  ON tags.id = link.tag_id
  WHERE is_public = 1
    AND tags.name = :tag
SQL;

        return self::queryCustom($sql, [':tag' => $tag]);
    }
	
	public function __construct($id=ActiveRecord::INDEX_NOT_IN_DB)
	{
		parent::__construct($id);

        $this->defineTable('snippets');
        $this->defineField('title');
        $this->defineField(
            'authorId',
            [
                'dbAlias' => 'author_id',
                'pdoType' => \PDO::PARAM_INT,
                'convFromDb' => function ($dbValue)
                {
                    return intval($dbValue);
                }
            ]);
        $this->defineField('code');
        $this->defineField(
            'languageId',
            [
                'dbAlias' => 'language_id',
                'pdoType' => \PDO::PARAM_INT
            ]);
        $this->defineField(
            'isPublic',
            [
                'dbAlias' => 'is_public',
                'pdoType' => \PDO::PARAM_BOOL,
                'convToDb' => function($value)
                {
                    return $value ? 1 : 0;
                },
                'convFromDb' => function($dbValue)
                {
                    return $dbValue == 1;
                }
            ]);
        $this->defineField(
            'lastChangedAt',
            [
                'dbAlias' => 'last_changed_at'
            ]);

        // Associations:

        $this->defineAssoc(
            'tags',
            'tbollmeier\\codeschnipsel\\model\\Tag',
            false, // <-- no composition
            [
                'linkTable' => 'snippets_tags',
                'sourceIdField' => 'snippet_id',
                'targetIdField' => 'tag_id',
                'onDeleteCallback' => [
                    'tbollmeier\\codeschnipsel\\model\\Snippet',
                    'onTagRemoved']
            ]);

	}

	public static function onTagRemoved($tag) {

	    // Delete tag if no further snippets are assigned
	    if (count($tag->snippets) == 0) {
	        $tag->delete();
        }

    }

    public function getAuthorName()
    {
        $user = new User($this->authorId);
        $user->load();

        return $user->name;
    }

	public function getLanguage()
    {
        $langId = intval($this->languageId);
        if (!empty($langId) && $langId != ActiveRecord::INDEX_NOT_IN_DB) {
            $lang = new Language($langId);
            $lang->load();
            return $lang->name;
        } else {
            return "";
        }
    }

    public function setLanguage($languageName)
    {
        $languages = Language::query();
        foreach ($languages as $language) {
            if ($language->name == $languageName) {
                $this->languageId = $language->getId();
                return;
            }
        }
        // Unknown language
        $this->languageId = null;

    }

    public function getTags()
    {
        $tagNames = array_map(function($tag) {
            return $tag->name;
        }, $this->tags);

        return implode(',', $tagNames);
    }

    public function setTags($tagsStr)
    {
        $tagNames = array_map('trim', explode(',', $tagsStr));

        $tags = [];
        foreach ($tagNames as $tagName) {
            $tag = Tag::findByName($tagName);
            if (!$tag) {
                $tag = new Tag();
                $tag->name = $tagName;
            }
            $tags[] = $tag;
        }

        $this->tags = $tags;

    }

    public function isVisibleForUser($user) : bool
    {
        if ($user && $user->getId() === $this->authorId) {
            return true;
        } else {
            return $this->isPublic;
        }
    }

}
