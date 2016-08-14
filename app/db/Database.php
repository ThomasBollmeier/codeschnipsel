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

        $lang = new Language('C/C++');
        $lang->save($dbConn);
        $lang = new Language('Clojure');
        $lang->save($dbConn);
        $lang = new Language('JavaScript');
        $lang->save($dbConn);
        $lang = new Language('PHP');
        $lang->save($dbConn);
        $lang = new Language('Python');
        $lang->save($dbConn);
        $lang = new Language('Ruby');
        $lang->save($dbConn);
        $lang = new Language('Haskell');
        $lang->save($dbConn);

    }

}