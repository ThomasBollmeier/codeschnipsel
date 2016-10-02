<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use tbollmeier\webappfound as waf;


switch ($argc) {
    case 3:
        $email = $argv[1];
        $password = $argv[2];
        $name = $email;
        break;
    case 4:
        $email = $argv[1];
        $password = $argv[2];
        $name = $argv[3];
        break;
    default:
        echo "Syntax: php $argv[0] email password [name]\n";
        exit(1);
}

$config = new tbollmeier\codeschnipsel\config\Configuration();
$dbConn = $config->getDbConnection();

// Active reccord management needs to know the database connection:
waf\db\ActiveRecord::setDbConnection($dbConn);

$user = tbollmeier\codeschnipsel\model\User::create($email, $password, $name);
$user->save();

exit(0);