<?php
/**
 * Created by PhpStorm.
 * User: drbolle
 * Date: 16.05.16
 * Time: 12:44
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

        self::setLanguages($conn);

    }

    private static function setLanguages(\PDO $dbConn)
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

        $existingLanguages = Language::getAll($dbConn);

        // From the insert list remove the languages that already exist
        foreach ($existingLanguages as $lang) {
            if (isset($names[$lang->name])) {
                unset($names[$lang->name]);
            }
        }

        // Insert the new ones:
        foreach (array_keys($names) as $name) {
            $lang = new Language($name);
            $lang->save($dbConn);
        }

    }

}