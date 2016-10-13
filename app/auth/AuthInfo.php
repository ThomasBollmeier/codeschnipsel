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

namespace tbollmeier\codeschnipsel\auth;

use tbollmeier\webappfound as waf;
use tbollmeier\codeschnipsel\model\User;


class AuthInfo
{
    public static function getCurrentUser()
    {
        $userId = waf\Session::getInstance()->userId;
        if ($userId) {
            $currentUser = new User($userId);
            $currentUser->load();
            return $currentUser;
        } else {
            return null;
        }
    }

    public static function getCurrentUserId()
    {
        return waf\Session::getInstance()->userId;
    }
    
    public static function isAuthorized()
    {
        
    }
}
