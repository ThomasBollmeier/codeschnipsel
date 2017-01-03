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
use tbollmeier\codeschnipsel\config\Configuration;
use tbollmeier\codeschnipsel\model\Snippet as SnippetModel;
use tbollmeier\codeschnipsel\model\Language;
use tbollmeier\codeschnipsel\view\Main;


class Snippet extends Controller
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->setActionPublic('index');
    }

    public function index($data)
    {
        $this->showDetails($data['snippet_id']);
    }
    
    public function newSnippet()
    {
        if ($this->isUserAuthorized('newSnippet')) {
            $readOnly = false;
            $this->showDetails(waf\db\ActiveRecord::INDEX_NOT_IN_DB, $readOnly);            
        } else {
            (new Home())->index();
        }
    }
    
    public function edit($urlParams)
    {
        $id = $urlParams['snippet_id'];
        $readOnly = !$this->isUserAuthorized('edit');
        
        $this->showDetails($id, $readOnly);
        
    }
    
    private function showDetails($id, $readOnly=true)
    {
        // Does snippet (still) exist?
        if (intval($id) !== waf\db\ActiveRecord::INDEX_NOT_IN_DB) {
            $snippet = new SnippetModel($id);
            if (!$snippet->isInDb()) {
                (new Home())->page404(); 
                return;
            }
        }

        $user = AuthInfo::getCurrentUser();

        if (!$snippet->isVisibleForUser($user)) {
            (new Home())->page404();
            return;
        }

        $view = new Main($user);

        $html = $this->getSnippetDetailHtml($id, $readOnly);
        $view->setContent($html);
        $html = $this->getSnippetDetailScript($readOnly);
        $view->setScripts($html);
        
        $view->render();
        
    }

    public function create()
    {
        if (!$this->isUserAuthorized('create')) {
            (new Home())->index();
            return;
        }

        $userId = AuthInfo::getCurrentUserId();

        $snippet = new SnippetModel();
        $snippet->title = $_POST['title'];
        $snippet->isPublic = $this->getIsPublic();
        $snippet->authorId = $userId;
        $snippet->code = $_POST['code'];
        $snippet->setLanguage($_POST['language']);
        $tagsStr = $_POST["tags"];
        $snippet->setTags($tagsStr);
        $snippet->lastChangedAt = (new \DateTime())->format("Y-m-d H:i:s"); 

        $snippet->save();

        $this->index(['snippet_id' => $snippet->getId()]);
    }

    public function update($data)
    {
        if (!$this->isUserAuthorized('update')) {
            (new Home())->index();
            return;
        }

        $snippet = new SnippetModel($data['snippet_id']);

        $snippet->title = $_POST['title'];
        $snippet->isPublic = $this->getIsPublic();
        $snippet->code = $_POST['code'];
        $snippet->setLanguage($_POST['language']);
        $tagsStr = $_POST['tags'];
        $snippet->setTags($tagsStr);
        $snippet->lastChangedAt = (new \DateTime())->format("Y-m-d H:i:s");

        $snippet->save();

        $this->edit(['snippet_id' => $snippet->getId()]);

    }

    private function getIsPublic()
    {
        if (!empty($_POST['isPublic'])) {
            return $_POST['isPublic'] == "X";
        } else {
            return false;
        }
    }

    private function getSnippetDetailHtml($id, $readOnly)
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

        $tagsStr = $snippet->getTags();
        
        $isEditAllowed = $this->isUserAuthorized('edit') &&
            AuthInfo::getCurrentUserId() === $snippet->authorId;

        $author = AuthInfo::getCurrentUserId() !== $snippet->authorId ?
            $snippet->getAuthorName() : false;

        $template = new waf\ui\Template('snippet_detail.html.php');

        return $template->getHtml([
            'id' => $snippet->getId(),
            'title' => $snippet->title,
            'isPublic' => $snippet->isPublic,
            'author' => $author,
            'code' => $snippet->code,
            'action' => $action,
            'languages' => $languages,
            'snippetLang' => $snippetLang,
            'tagsStr' => $tagsStr,
            'baseUrl' => $baseUrl,
            'readOnly' => $readOnly,
            'isEditAllowed' => $isEditAllowed
        ]);
    }

    private function getSnippetDetailScript($readOnly)
    {
        $template = new waf\ui\Template('snippet_detail_js.html.php');

        return $template->getHtml([
            'readOnly' => $readOnly
        ]);
    }

}