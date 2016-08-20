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
use tbollmeier\codeschnipsel\config\Configuration;
use tbollmeier\codeschnipsel\model\Snippet as SnippetModel;
use tbollmeier\codeschnipsel\model\Language;
use tbollmeier\codeschnipsel\view\Main;


class Snippet
{

    public function index($data)
    {
        $id = $data['snippet_id'] ?? waf\db\ActiveRecord::INDEX_NOT_IN_DB;

        $user = waf\Session::getInstance()->currentUser;

        $view = new Main($user);

        if ($user) {
            $html = $this->getSnippetDetailHtml($id);
            $view->setContent($html);
            $html = $this->getSnippetDetailScript();
            $view->setScripts($html);
        }

        $view->render();

    }

    public function create($data)
    {

        $session = waf\Session::getInstance();

        $snippet = new SnippetModel();
        $snippet->title = $_POST['title'];
        $snippet->authorId = $session->currentUser->getId();
        $snippet->code = $_POST['code'];
        $snippet->setLanguage($_POST['language']);

        $snippet->save();

        $this->index(['snippet_id' => $snippet->getId()]);
    }

    public function update($data)
    {
        $snippet = new SnippetModel($data['snippet_id']);

        $snippet->title = $_POST['title'];
        $snippet->code = $_POST['code'];
        $snippet->setLanguage($_POST['language']);

        $snippet->save();

        $this->index(['snippet_id' => $snippet->getId()]);

    }

    private function getSnippetDetailHtml($id)
    {
        $snippet = new SnippetModel($id);
        $baseUrl = Configuration::getInstance()->getBaseUrl();

        if ($id != waf\db\ActiveRecord::INDEX_NOT_IN_DB) {
            $action = $baseUrl . "/snippets/". $id;
            $snippet->load();
        } else {
            $action = $baseUrl . "/snippets";
            $snippet->title = "";
            $snippet->code = "";
        }

        $languages = Language::query();
        $snippetLang = $snippet->getLanguage();

        $template = new waf\Template('snippet_detail.html.php');

        return $template->getHtml([
            'title' => $snippet->title,
            'code' => $snippet->code,
            'action' => $action,
            'languages' => $languages,
            'snippetLang' => $snippetLang,
            'baseUrl' => $baseUrl
        ]);
    }

    private function getSnippetDetailScript()
    {
        $template = new waf\Template('snippet_detail_js.html.php');

        return $template->getHtml();
    }

}