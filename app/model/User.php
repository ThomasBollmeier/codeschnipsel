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

use tbollmeier\codeschnipsel\core\Session;
use tbollmeier\codeschnipsel\core\Model;


class User extends Model
{

    private $email;
    private $passwordHash;
    private $name;
	
	public static function findByEmail(\PDO $dbConn, $email)
	{
	    $res = null;

	    $sql = 'SELECT * FROM users WHERE email = :email';
        $stmt = $dbConn->prepare($sql);

        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        if ($row) {
            $user = new User();
            $user->id = $row['id'];
            $user->setEmail($row['email']);
            $user->setPasswordHash($row['password_hash']);
            $user->setName($row['nickname']);
            $res = $user;
        }

        $stmt->closeCursor();

        return $res;

	}

	public static function create($email, $password, $name='')
    {
        $user = new User();

        $user->setEmail($email);
        $user->setPassword($password);
        $user->setName(!empty($name) ? $name : $email);

        return $user;
    }

	public function signIn($password)
	{
		$signedIn = password_verify($password, $this->passwordHash);
		
		if ($signedIn) {
			Session::getInstance()->currentUser = $this;
		}
		
		return $signedIn;
	}
	
	public function signOut()
	{
		Session::deleteInstance();
	}
	
	public function isSignedIn()
	{
		$currentUser = Session::getInstance()->currentUser;
		
		return $this === $currentUser;
	}

	public function load(\PDO $dbConn)
    {
        // TODO: Implement load() method.
    }

    public function save(\PDO $dbConn)
    {
        if ($this->id == -1) {
            $sql = 'INSERT INTO users (email, password_hash, nickname) ';
            $sql .= 'VALUES (:email, :password_hash, :nickname)';
            $params = [
                ':email' => $this->email,
                ':password_hash' => $this->passwordHash,
                ':nickname' => $this->name
            ];
        } else {
            $sql = '';
            $params = [];
        }

        $stmt = $dbConn->prepare($sql);
        $stmt->execute($params);

        if ($this->id == -1) {
            $this->id = intval($dbConn->lastInsertId());
        }

    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool|string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * @param bool|string $passwordHash
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    public function setPassword($password)
    {
        $this->passwordHash = password_hash($password, \PASSWORD_DEFAULT);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

}
