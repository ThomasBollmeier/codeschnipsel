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
require_once 'vendor/autoload.php';

use tbollmeier\codeschnipsel\core\Session;
use tbollmeier\codeschnipsel\db as db;
use tbollmeier\codeschnipsel\core as core;
use tbollmeier\codeschnipsel\config\Configuration;


function initDb(Configuration $config, Session $session)
{
    $initialized = $session->get('initialized', false);

    if (!$initialized) {
        db\Database::setup($config->getDbConnection());
        $session->initialized = true;
    }
}

$config = Configuration::getInstance();

initDb($config, Session::getInstance());

$router = new core\Router([
    'controllerNS' => 'tbollmeier\\codeschnipsel\\controller',
    'defaultCtrlAction' => 'Home.index',
    'baseUrl' => $config->getBaseUrl()
]);

$router->registerAction(
    'GET',
    'snippets/:snippet_id',
    'Snippet.index'
);
$router->registerAction(
    'GET',
    'new-snippet',
    'Snippet.index'
);
$router->registerAction(
    'GET',
    'snippets/delete/:snippet_id',
    'Home.delete'
);

$router->registerAction(
    'POST',
    'signin',
    'Home.signin'
);
$router->registerAction(
    'POST',
    'signout',
    'Home.signout'
);
$router->registerAction(
    'POST',
    'snippets',
    'Snippet.create'
);
$router->registerAction(
    'POST',
    'snippets/:snippet_id',
    'Snippet.update'
);

$router->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

