<?php

include 'database.php';

class User {
	public int $id;
	public  string $username;
	public  bool $isadmin;

  function __construct($id, $username, $isadmin) {
		$this->id = $id;
		$this->username = $username;
		$this->isadmin = $isadmin;
	}	
}

class Users {
	private $_users = array();
	private $_isloaded = false;
	
	public function loadFromDatabase() {
		global $DBConnected, $DBBasketball;
		unset($this->_users);
		$this->_users = array();
		
		if ($DBConnected and (!$this->_isloaded)) {
		  foreach($DBBasketball->query('SELECT * FROM users') as $row) {
				$User = new User($row['id'], $row['username'], $row['isadmin']);
			  $this->_users[] = $User;
			};
			$this->_isloaded = true;
			return true;
		} else {
			return false;
		}
	}	
	
  public function addUser($username, $password, $isadmin) {
    global $DBConnected, $DBBasketball;
		$returnvalue = 0;
		
		if ($DBConnected) {
			// Check if the username already exists
			$stmt = $DBBasketball->prepare('SELECT * FROM users WHERE username = ?');
			$stmt->execute([$username]);
			$result = $stmt->fetch();
			
			if ($result) {
				$returnvalue = -1;
			} else {			
			  // if it doesn't exist, add it
				// first, hash the pasword
				$password = password_hash($password, PASSWORD_DEFAULT);
				
				// now add
			  $stmt = $DBBasketball->prepare('INSERT INTO users (username, password, isadmin) VALUES (?, ?, ?)');
			  try {
			    $stmt->execute([$username, $password, $isadmin]);
				  $returnvalue = 1;
			  } catch (PDOException $e) {
			    $returnvalue = -2;
        }
		  }
		}
		
		return $returnvalue;
  }
	
	public function removeUser($id) {
		global $DBConnected, $DBBasketball;
		$returnvalue = 0;
		if ($DBConnected) {
		  $stmt = $DBBasketball->prepare('DELETE FROM users WHERE id = ?');
			$del = $stmt->execute([$id]);
			$count = $stmt->rowCount();
			if ($count == 1) {
				$returnvalue = 1;
			} else {
				$returnvalue = -1;
			}
		}
		return $returnvalue;
	}
	
	public function loginUser($username, $password) {
		global $DBConnected, $DBBasketball;
		$returnvalue = "0~0~0";
		if ($DBConnected) {
	    // First find the user based on the username
		  $stmt = $DBBasketball->prepare('SELECT * FROM users WHERE username = ?');
			$stmt->execute([$username]);
			$result = $stmt->fetch();
			
			if ($result) {
				$hash = $result['password'];
				// Ouch, workaround first user cannot add hashed user :(
				if (password_verify($password, $hash) || ($password == $hash)) { 
			    $returnvalue = "1~".$result['id']."~".$result['isadmin'];
		    } else {
			    // password incorrect
					$returnvalue = "-2~0~0";
        }		
      } else {
				// username incorrect
		    $returnvalue = "-1~0~0";
			}
		}
		
		return $returnvalue;
	}
	
	public function getUser($index) {
		return $this->_users[$index];
	}
	
	public function getCount() {
		return count($this->_users);
	}
}
?>