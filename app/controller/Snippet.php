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
use tbollmeier\codeschnipsel\config\Configuration;
use tbollmeier\codeschnipsel\model\Snippet as SnippetModel;


class Snippet
{
    private $template;

    public function __construct()
    {
        $this->template = new core\Template('index.html.php');
    }

    public function index($data)
    {
        $id = $data['snippet_id'];

        $session = core\Session::getInstance();
        $dbConn = Configuration::getInstance()->getDbConnection();

        $mainContent = !$session->currentUser ?
            $template = (new core\Template('welcome.html.php'))->getHtml() :
            $this->getSnippetDetailHtml($dbConn, $id);

        echo $this->template->getHtml([
            'currentUser' => $session->currentUser,
            'mainContent' => $mainContent
        ]);

    }

    private function getSnippetDetailHtml($dbConn, $id)
    {
        $snippet = new SnippetModel($id);
        $snippet->load($dbConn);

        $template = new core\Template('snippet_detail.html.php');

        return $template->getHtml(['snippet' => $snippet]);
    }

}