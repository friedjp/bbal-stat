<?php

include 'database.php';

// https://www.nba-live.com/nbalivewiki/index.php?title=Playstyles
$PLAYERSTYLES = array("Guard - Point Shooter", "Guard - Slasher", "Guard - Backcourt Defender", "Guard - Playmaker", "Wing - Wing Shooter", "Wing - Winger Scorer", "Wing - Wing Defender", "Wing - Hybrid Wing", "Big - Rim Protector", "Big - Strech Four", "Big - Post Anchor", "Big - Post Scorer");

class Player {
	public int $id;
	public string $name;
	public $dob;
	public string $playerstyle;
	public bool $injured;
	public array $teams;
	
	function __construct($id, $name) {
		$this->id = $id;
		$this->name = $name;
		$this->dob = "";
		$this->playerstyle = "";
		$this->injured = 0;
	}
	
        public function getAvgPercentage() {
                global $DBConnected, $DBBasketball;
            
		$statement = "SELECT ( SUM(made2point)+ SUM(made3point)+ SUM(madelayup) )/ "
                        . "( SUM(made2point)+ SUM(missed2point)+ SUM(made3point)+ SUM(missed3point)+ SUM(madelayup)+ SUM(missedlayup) ) "
                        . "AS AvgShooting FROM attendance LEFT JOIN "
                        . "players ON attendance.playerid = players.id "
                        . "WHERE attendance.playerid = ".$this->id;
                $row = $DBBasketball->query($statement)->fetch();
                return $row[0]*100;
	}
        
        public function get2PointPercentage() {
                global $DBConnected, $DBBasketball;
                
		$statement = "SELECT SUM(made2point)/(SUM(made2point)+SUM(missed2point)) AS TwoPointPerc "
                        . "FROM attendance LEFT JOIN players ON attendance.playerid = players.id "
                        . "WHERE attendance.playerid = ".$this->id;
                $row = $DBBasketball->query($statement)->fetch();
                return $row[0]*100;
	}
        
        public function get3PointPercentage() {
		global $DBConnected, $DBBasketball;
                
		$statement = "SELECT SUM(made3point)/(SUM(made3point)+SUM(missed3point)) AS ThreePointPerc FROM attendance LEFT JOIN players ON attendance.playerid = players.id WHERE attendance.playerid = ".$this->id;
                $row = $DBBasketball->query($statement)->fetch();
                return $row[0]*100;
	}
        
        public function getLayupPercentage() {
		global $DBConnected, $DBBasketball;
                
		$statement = "SELECT SUM(madelayup)/(SUM(madelayup)+SUM(missedlayup)) AS LayupPerc FROM attendance LEFT JOIN players ON attendance.playerid = players.id WHERE attendance.playerid = ".$this->id;
                $row = $DBBasketball->query($statement)->fetch();
                return $row[0]*100;
	}
        
        public function getAttendanceRate() {
		global $DBConnected, $DBBasketball;
                
		$statement = "SELECT SUM(attendance.presentsession)
                                FROM attendance
                                WHERE attendance.playerid =".$this->id;
                 $presentsessions = $DBBasketball->query($statement)->fetch();
                
                $statement = "SELECT COUNT(attendance.presentsession)
                                FROM attendance
                                WHERE attendance.playerid =". $this->id;
                $totalsessions = $DBBasketball->query($statement)->fetch();
                
                $attendancerate = ($presentsessions[0] / $totalsessions[0]);
                
                 return $attendancerate*100;
	}
        
        
        
  public function addTeam($teamid) {
		global $DBConnected, $DBBasketball;
		$bSuccess = false;
		if ($this->id > -1) {
			try {
			  if ($DBBasketball->query('INSERT INTO playerstoteams (playerid, teamid) '
                                  . 'VALUES ('.$this->id.','.$teamid.')')) {
          $bSuccess = true;
        }					
			} catch (Exception $e) {
				$bSuccess = false;
			}
		}
		return $bSuccess;
	}
	
  public function removeTeams() {
		global $DBConnected, $DBBasketball;
		$bSuccess = false;
		if ($this->id > -1) {
			try {
			  if ($DBBasketball->query('DELETE FROM playerstoteams WHERE playerid='.$this->id)) {
          $bSuccess = true;
        }					
			} catch (Exception $e) {
				$bSuccess = false;
			}
		}
		return $bSuccess;
	}		
}

class Players {
	private $_players = array();
	private $_isloaded = false;
	
	private function getAttendanceRate($id) {
		return 0; 
	}
        
    
	

	private function getPlayerTeams($Player) {
		global $DBConnected, $DBBasketball;
	  if ($DBConnected and (!$this->_isloaded)) {	
		  foreach($DBBasketball->query('SELECT teams.name FROM '
                          . '((players LEFT JOIN playerstoteams ON players.id = playerstoteams.playerid) '
                          . 'LEFT JOIN teams ON playerstoteams.teamid = teams.id) WHERE players.id='.$Player->id) as $row) {
				$Player->teams[] = $row[0];
		  }  
		}
	}
	
	public function loadFromDatabase() {
		global $DBConnected, $DBBasketball;
		unset($this->_players);
		$this->_players = array();
		
		if ($DBConnected and (!$this->_isloaded)) {
		  foreach($DBBasketball->query('SELECT * FROM players ORDER BY name') as $row) {
				$Player = new Player($row['id'], $row['name']);
				$Player->playerstyle = $row['playerstyle'];
				$Player->dob = $row['dob'];
				$Player->injured = $row['injured'];
				$this->getPlayerTeams($Player);
				
				$this->_players[] = $Player;
			};
			$this->_isloaded = true;
			return true;
		} else {
			return false;
		}
	}
        
           
	
	public function addPlayer($Player) {
		global $DBConnected, $DBBasketball;
		$success = false;
		if ($DBConnected) {
			$int_injured = $Player->injured ? 1 : 0;
			echo 'wraf5<br>';
			// Insert
			try {
			  if ($DBBasketball->query('INSERT INTO players (name, dob, playerstyle, injured, shootingpercentage, attendancerate) '
                                  . 'VALUES ("'.$Player->name.'","'.$Player->dob.'","'.$Player->playerstyle.'","'.$int_injured.'.", 0,0)')) {
				  $this->_isloaded = false;
			    $this->loadFromDatabase();
				  $success = true;
			  }
			}	catch (Exception $e) {
	      $success = false;
		  }		
	  }
		return $success;
	}
	
	public function removePlayer($id) {
		global $DBConnected, $DBBasketball;
		$success = false;
		if ($DBConnected) {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			if ($DBBasketball->query('DELETE FROM players WHERE ID='.$id)) {
				if ($DBBasketball->query('DELETE FROM playerstoteams WHERE playerid='.$id)) {
                                  $DBBasketball->query('DELETE FROM attendance WHERE playerid='.$id);
				  $success = true;
				}
			}
		}
		return $success;
	}
	
	public function editPlayer($Player) {
                global $DBConnected, $DBBasketball;
		$success = false;
		if ($DBConnected) {
			try {
				$int_injured = $Player->injured ? 1 : 0;
			  if ($DBBasketball->query('UPDATE  players SET name="'.$Player->name.'", dob="'.$Player->dob.'", playerstyle="'.$Player->playerstyle.'", injured='.$int_injured.' WHERE id='.$Player->id)) {
				  $this->_isloaded = false;
			    $this->loadFromDatabase();
				  $success = true;
			  }
			} catch (Exception $e) {
				$success = false;
			}
		}
		return $success;		
	}
        
        public function editShootingAttenPlayer($Player) {
                global $DBConnected, $DBBasketball;
		$success = false;
		if ($DBConnected) {
			try {
				$int_injured = $Player->injured ? 1 : 0;
			  if ($DBBasketball->query('UPDATE  players SET shootingpercentage="'.$Player->getAvgPercentage().'", attendancerate="'.$Player->getAttendanceRate().'" WHERE id='.$Player->id)) {
				  $this->_isloaded = false;
			    $this->loadFromDatabase();
				  $success = true;
			  }
			} catch (Exception $e) {
				$success = false;
			}
		}
		return $success;		
	}
	
	public function findPlayer($name) {
		$iCount = 0;
		$bFound = false;
		
		while (($iCount < count($this->_players)) && (!$bFound)) {
			if ($this->_players[$iCount]->name == $name) {
				$bFound = true;
			} else {
				$iCount++;
			}			
		}
		
		if ($bFound) {
			return $this->_players[$iCount];
		} else {
			return null;
		}
	}
	
        public function findPlayerByID($id) {
		$iCount = 0;
		$bFound = false;
		
		while (($iCount < count($this->_players)) && (!$bFound)) {
			if ($this->_players[$iCount]->id == $id) {
				$bFound = true;
			} else {
				$iCount++;
			}			
		}
		
		if ($bFound) {
			return $this->_players[$iCount];
		} else {
			return null;
		}
	}
	public function getPlayer($index) {
		return $this->_players[$index];
	}
	
	public function getCount() {
		return count($this->_players);
	}
}	

?>
