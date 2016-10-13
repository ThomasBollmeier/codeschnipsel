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

use tbollmeier\webappfound as waf;
use tbollmeier\codeschnipsel\auth\AuthInfo;
use tbollmeier\codeschnipsel\model\User;
use tbollmeier\codeschnipsel\model\Snippet as SnippetModel;
use tbollmeier\codeschnipsel\view\Main;
use tbollmeier\codeschnipsel\config\Configuration;


class Home extends Controller
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->setActionPublic('index');
        $this->setActionPublic('page404');
        $this->setActionPublic('signin');
        
    }

    public function index()
    {
        $user = AuthInfo::getCurrentUser();
        $view = new Main($user);

        if ($user) {
            $view->setContent($this->getSnippetOverviewHtml($user));
            $view->setScripts($this->getScripts());
        }

        $view->render();
    }
    
    public function page404()
    {
        $view = new Main(AuthInfo::getCurrentUser());
        
        $template = new waf\ui\Template('page404.html.php');
        $view->setContent($template->getHtml());
        
        $view->render();
    }

    public function delete($urlParams)
    {
        if (!$this->isUserAuthorized('delete')) {
            return;
        }
        
        $snippet = new SnippetModel($urlParams['snippet_id']);
        $snippet->delete();
    }

    public function signin()
    {
        
        while (true) {

            if (!isset($_POST['email'])) {
                break;
            }

            $email = filter_var($_POST['email'], \FILTER_VALIDATE_EMAIL);
            if (empty($email)) {
                break;
            }

            $user = User::findByEmail($email);

            if ($user) {
                $user->signIn($_POST['password']);
            }

            break;
        }

        $this->index();
    }

    public function signout()
    {
        if ($this->isUserAuthorized('signout')) {
            waf\Session::deleteInstance();
        }

        $this->index();
    }

    private function getSnippetOverviewHtml($author)
    {
        $snippets = SnippetModel::getAllOf($author);

        $template = new waf\ui\Template('snippets_overview.html.php');

        return $template->getHtml([
            'baseUrl' => Configuration::getInstance()->getBaseUrl(),
            'snippets' => $snippets
        ]);
    }

    public function getScripts()
    {
        $template = new waf\ui\Template('snippets_overview_js.html.php');

        return $template->getHtml();
    }

}