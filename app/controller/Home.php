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

namespace tbollmeier\codeschnipsel\controller;

use tbollmeier\codeschnipsel\core as core;
use tbollmeier\codeschnipsel\model\User;
use tbollmeier\codeschnipsel\model\Snippet;
use tbollmeier\codeschnipsel\config\Configuration;


class Home
{
    private $template;

    public function __construct()
    {
        $this->template = new core\Template('index.html.php');
    }

    public function index($data)
    {
        $session = core\Session::getInstance();
        $dbConn = Configuration::getInstance()->getDbConnection();

        $mainContent = !$session->currentUser ?
            $template = (new core\Template('welcome.html.php'))->getHtml() :
            $this->getSnippetOverviewHtml($dbConn, $session->currentUser);

        echo $this->template->getHtml([
            'currentUser' => $session->currentUser,
            'mainContent' => $mainContent
        ]);
    }

    public function signin($data)
    {
        while (true) {

            if (!isset($_POST['email'])) {
                break;
            }

            $email = filter_var($_POST['email'], \FILTER_VALIDATE_EMAIL);
            if (empty($email)) {
                break;
            }

            $dbConn = Configuration::getInstance()->getDbConnection();

            $user = User::findByEmail($dbConn, $email);

            if ($user) {
                $user->signIn($_POST['password']);
            }

            break;
        }

        $this->index([]);
    }

    public function signout($data)
    {
        core\Session::deleteInstance();

        $this->index([]);
    }

    private function getSnippetOverviewHtml($dbConn, $author)
    {
        $snippets = Snippet::getAllOf($dbConn, $author);

        $template = new core\Template('snippets_overview.html.php');

        return $template->getHtml(['snippets' => $snippets]);

    }

}