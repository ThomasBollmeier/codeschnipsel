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
use tbollmeier\codeschnipsel\view\Main;
use tbollmeier\codeschnipsel\config\Configuration;


class Home
{

    public function index($data=[])
    {
        $user = core\Session::getInstance()->currentUser;
        $dbConn = Configuration::getInstance()->getDbConnection();

        $view = new Main($user);

        if ($user) {
            $view->setContent($this->getSnippetOverviewHtml($dbConn, $user));
            $view->setScripts($this->getScripts());
        }

        $view->render();
    }

    public function delete($data)
    {
        $snippet = new Snippet($data['snippet_id']);
        $dbConn = Configuration::getInstance()->getDbConnection();
        $snippet->delete($dbConn);

        $this->index();

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

        $this->index();
    }

    public function signout($data)
    {
        core\Session::deleteInstance();

        $this->index();
    }

    private function getSnippetOverviewHtml($dbConn, $author)
    {
        $snippets = Snippet::getAllOf($dbConn, $author);

        $template = new core\Template('snippets_overview.html.php');

        return $template->getHtml(['snippets' => $snippets]);

    }

    public function getScripts()
    {
        $template = new core\Template('snippets_overview_js.html.php');

        return $template->getHtml();
    }

}