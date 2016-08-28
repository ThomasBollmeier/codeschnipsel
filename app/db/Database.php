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

namespace tbollmeier\codeschnipsel\db;

use tbollmeier\codeschnipsel\model\Language;


class Database
{

    public static function setup(\PDO $conn)
    {

        $sql = <<<'SQL'
 CREATE TABLE IF NOT EXISTS `users` (
    `id`            int(11)       NOT NULL  AUTO_INCREMENT,
    `email`         varchar(80)   NOT NULL,
    `password_hash` varchar(100)  NOT NULL,
    `nickname`      varchar(50)   DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
SQL;
        $conn->exec($sql);

        $sql = <<<'SQL'
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
SQL;
        $conn->exec($sql);

        $sql = <<<'SQL'
CREATE TABLE IF NOT EXISTS `snippets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `author_id` int(11) NOT NULL,
  `code` text NOT NULL,
  `language_id` int(11) DEFAULT NULL,
  `last_changed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  CONSTRAINT `author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
SQL;
        $conn->exec($sql);

        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `tags` ( 
  `id` INT NOT NULL AUTO_INCREMENT , 
  `name` VARCHAR(80) NOT NULL , 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8
SQL;
        $conn->exec($sql);

        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `snippets_tags` ( 
  `snippet_id` INT NOT NULL , 
  `tag_id` INT NOT NULL , 
  PRIMARY KEY (`snippet_id`, `tag_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8
SQL;
        $conn->exec($sql);

        self::setLanguages();

    }

    private static function setLanguages()
    {
        $names = [
            'C/C++' => true,
            'Clojure' => true,
            'Haskell' => true,
            'JavaScript' => true,
            'PHP' => true,
            'Python' => true,
            'Ruby' => true
        ];

        $existingLanguages = Language::query();

        // From the insert list remove the languages that already exist
        foreach ($existingLanguages as $lang) {
            if (isset($names[$lang->name])) {
                unset($names[$lang->name]);
            }
        }

        // Insert the new ones:
        foreach (array_keys($names) as $name) {
            $lang = new Language();
            $lang->name = $name;
            $lang->save();
        }

    }

}