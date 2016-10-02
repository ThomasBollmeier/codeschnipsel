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

use tbollmeier\webappfound as waf;
use tbollmeier\codeschnipsel\db as db;
use tbollmeier\codeschnipsel\config\Configuration;


function setupDatabase(Configuration $config, waf\Session $session)
{
    $initialized = $session->get('initialized', false);

    if (!$initialized) {
        db\Database::setup($config->getDbConnection());
        $session->initialized = true;
    }
}

$config = Configuration::getInstance();

// configure active reccord management (must be done before DB setup!):
waf\db\ActiveRecord::setDbConnection($config->getDbConnection());

setupDatabase($config, waf\Session::getInstance());

// Setup template handling
waf\ui\Template::setTemplateDir(__DIR__.'/app/template');

// Setup routing
$router = new waf\Router([
    'controllerNS' => 'tbollmeier\\codeschnipsel\\controller',
    'defaultCtrlAction' => 'Home.index',
    'baseUrl' => $config->getBaseUrl()
]);

$routesData = <<<DATA

GET snippets Home.index,

GET snippets/new Snippet.new,
POST snippets Snippet.create,
GET snippets/:snippet_id Snippet.index,
GET snippets/:snippet_id/edit Snippet.edit,
POST snippets/:snippet_id Snippet.update,
DELETE snippets/:snippet_id Home.delete,

POST signin Home.signin,
POST signout Home.signout

DATA;

$router->registerActions($routesData);

$router->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
