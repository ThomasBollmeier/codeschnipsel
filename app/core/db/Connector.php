<?php
/**
 * Created by PhpStorm.
 * User: drbolle
 * Date: 15.05.16
 * Time: 20:05
 */

namespace tbollmeier\codeschnipsel\core\db;


class Connector
{
    /**
     * Create a connection from a configuration file:
     *
     * @param $configFile   string  path to config file
     * @return mixed                PDO connection instance or false
     */
    public function createConfigConnection($configFile)
    {
        try {
            
            $options = $this->parseConfigFile($configFile);
            $dsn = $this->composeDSN($options);
            $user = $options['user'];
            $password = $options['password'];

            return new \PDO($dsn, $user, $password);

        } catch (\Exception $error) {
            return false;
        }
    }

    private function parseConfigFile($configFile)
    {

        $content = file_get_contents($configFile);
        if ($content === false) {
            throw new \Exception("Cannot read file {$configFile}.");
        }

        $convertIntoAssoc = true;
        $options = json_decode($content, $convertIntoAssoc);

        $defaults = [
            'type' => 'mysql',
            'host' => 'localhost',
            'user' => 'root',
            'password' => ''
        ];

        foreach ($defaults as $key => $value) {
            if (!array_key_exists($key, $options)) {
                $options[$key] = $value;
            }
        }

        return $options;
    }

    private function composeDSN($options)
    {
        $dsn = "";
        if ($options['type'] == 'mysql') {
            $dsn .= 'mysql:';
        } else {
            throw new \Exception("Unknown DB type {$options['type']}.");
        }
        
        $dsn .= 'host='.$options['host'];

        if (array_key_exists('port', $options)) {
            $dsn .= ";port=".$options['port'];
        }

        $dsn .= ';dbname='.$options['dbname'];

        if (array_key_exists('charset', $options)) {
            $dsn .= ";port=".$options['charset'];
        }

        return $dsn;
    }

}