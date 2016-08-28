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


class Tag extends ActiveRecord
{
    public static function findByName($name)
    {
        $tags = Tag::query([
            'filter' => 'name = :name',
            'params' => [':name' => $name]
        ]);

        return !empty($tags) ? $tags[0] : null;
    }


    public function __construct($id=ActiveRecord::INDEX_NOT_IN_DB)
    {
        parent::__construct($id);

        $this->defineTable('tags');
        $this->defineField('name');

        // Associations:

        $this->defineAssoc(
            'snippets',
            'tbollmeier\\codeschnipsel\\model\\Snippet',
            false, // <-- no composition
            [
                'linkTable' => 'snippets_tags',
                'sourceIdField' => 'tag_id',
                'targetIdField' => 'snippet_id'
            ]);

    }

}