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

namespace tbollmeier\codeschnipsel\config;

use tbollmeier\webappfound\db\Connector;


class Configuration
{
    const DB_CONFIG_FILE = __DIR__ . DIRECTORY_SEPARATOR . 'db.json';
    const SITE_CONFIG_FILE = __DIR__ . DIRECTORY_SEPARATOR . 'site.json';

    private static $instance= null;
    private $dbConn = null;
    private $baseUrl = null;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Configuration();
        }

        return self::$instance;
    }

    public function getDbConnection() : \PDO
    {
        if (!$this->dbConn) {
            $connector = new Connector;
            $this->dbConn = $connector->createConfigConnection(self::DB_CONFIG_FILE);
        }

        return $this->dbConn;
    }

    public function getBaseUrl()
    {
        if ($this->baseUrl === null) {
            $content = file_get_contents(self::SITE_CONFIG_FILE);
            $convertIntoAssoc = true;
            $siteOptions = json_decode($content, $convertIntoAssoc);
            $url = $siteOptions['baseUrl'];
            if (!empty($url)) {
                $url = '/' . trim($url, '/');
            }
            $this->baseUrl = $url;
        }

        return $this->baseUrl;
    }

}