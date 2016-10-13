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

use tbollmeier\codeschnipsel\auth\AuthInfo;


abstract class Controller
{
    const ACTION_AUTH_LEVEL_ALL = 1;
    const ACTION_AUTH_LEVEL_USER_ONLY = 2;
    
    private $actionAuthLevels;
    
    public function __construct()
    {
        $this->actionAuthLevels = [];
    }
    
    protected function setAuthLevel($actionName, $level)
    {
        $this->actionAuthLevels[$actionName] = $level;
    }
    
    protected function setActionPublic($actionName)
    {
        $this->setAuthLevel($actionName, self::ACTION_AUTH_LEVEL_ALL);
    }
    
    protected function getAuthLevel($actionName)
    {
        return array_key_exists($actionName, $this->actionAuthLevels) ?
            $this->actionAuthLevels[$actionName] :
            self::ACTION_AUTH_LEVEL_USER_ONLY;
    }
    
    protected function isUserAuthorized($actionName)
    {
        if (AuthInfo::getCurrentUserId() !== null) {
            return true;
        } else {
            $requiredLevel = $this->getAuthLevel($actionName);
            return $requiredLevel === self::ACTION_AUTH_LEVEL_ALL;
        }
    }
}