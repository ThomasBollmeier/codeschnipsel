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

namespace tbollmeier\codeschnipsel\view;

use tbollmeier\webappfound\Template;
use tbollmeier\codeschnipsel\config\Configuration;


class Main
{
    private $template;
    private $user;
    private $content;
    private $scriptCode;

    public function __construct($user)
    {
        $this->template = new Template('index.html.php');
        $this->user = $user;
        if ($this->user) {
            $this->content = '';
        } else {
            $template = new Template('welcome.html.php');
            $this->content = $template->getHtml();
        }
        $this->scriptCode = '';
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setScripts($scriptCode)
    {
        $this->scriptCode = $scriptCode;
        return $this;
    }

    public function render()
    {
        echo $this->template->getHtml([
            'currentUser' => $this->user,
            'mainContent' => $this->content,
            'scripts' => $this->scriptCode,
            'baseUrl' => Configuration::getInstance()->getBaseUrl()
        ]);

    }

}