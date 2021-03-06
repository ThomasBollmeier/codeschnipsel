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

namespace tbollmeier\codeschnipsel\model;

use tbollmeier\webappfound\Session;
use tbollmeier\webappfound\db\ActiveRecord;


class User extends ActiveRecord
{
	
	public static function findByEmail($email)
	{
	    $users = User::query([
	        'filter' => 'email = :email',
            'params' => [':email' => $email]
        ]);

        return count($users) == 1 ? $users[0] : null;
	}

    public function __construct($id = ActiveRecord::INDEX_NOT_IN_DB)
    {
        parent::__construct($id);

        $this->defineTable('users');
        $this->defineField('email');
        $this->defineField('passwordHash', ['dbAlias' => 'password_hash']);
        $this->defineField('name', ['dbAlias' => 'nickname']);
    }

	public static function create($email, $password, $name='')
    {
        $user = new User();

        $user->email = $email;
        $user->setPassword($password);
        $user->name = !empty($name) ? $name : $email;

        return $user;
    }

	public function signIn($password)
	{
		$signedIn = password_verify($password, $this->passwordHash);
		
		if ($signedIn) {
			$session = Session::getInstance();
			$session->userId = $this->getId();
		}
		
		return $signedIn;
	}
	
	public function signOut()
	{
		Session::deleteInstance();
	}
	
	public function isSignedIn()
	{
		$userId = Session::getInstance()->userId;
		
		return $this->getId() === $userId;
	}

    public function setPassword($password)
    {
        $this->passwordHash = password_hash($password, \PASSWORD_DEFAULT);
    }

}
