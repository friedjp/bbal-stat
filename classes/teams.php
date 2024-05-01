<?php

include 'database.php';
$TRAININGSESSIONS = array("Trash team", "Layup Drill", "2 Pointer Drill", "3 Pointer Drill",);

class Team {
	public int $id;
	public string $name;
	public array $players;
	
	function __construct($id, $name) {
		$this->id = $id;  
		$this->name = $name;
	}
	
	public function addPlayer($playerid) {
		global $DBConnected, $DBBasketball;
		$bSuccess = false;
		if ($this->id > -1) {
			try {
			  if ($DBBasketball->query('INSERT INTO playerstoteams (teamid, playerid) '
                                  . 'VALUES ('.$this->id.','.$playerid.')')) {
          $bSuccess = true;
        }					
			} catch (Exception $e) {
				$bSuccess = false;
			}
		}
		return $bSuccess;
	}
        
        public function GetAvgPercentage(){
            global $DBConnected, $DBBasketball;
            
            $statement = "SELECT (SUM(made2point) + SUM(made3point) + SUM(madelayup)) / (SUM(made2point) + SUM(missed2point) + SUM(made3point) + SUM(missed3point) + SUM(madelayup) + SUM(missedlayup)) AS AvgPointPerc FROM attendance LEFT JOIN playerstoteams ON attendance.playerid = playerstoteams.playerid WHERE playerstoteams.teamid =".$this->id;
            $row = $DBBasketball->query($statement)->fetch();
            return $row[0]*100;
        }
        
        public function Get2PointPercentage(){
            global $DBConnected, $DBBasketball;
            
            $statement = "SELECT SUM(made2point)/(SUM(made2point)+SUM(missed2point)) AS TwoPointPerc FROM attendance LEFT JOIN playerstoteams ON attendance.playerid = playerstoteams.playerid WHERE playerstoteams.teamid =".$this->id;
            $row = $DBBasketball->query($statement)->fetch();
            return $row[0]*100;
        }
        
        public function Get3PointPercentage(){
            global $DBConnected, $DBBasketball;
            
            $statement = "SELECT SUM(made3point)/(SUM(made3point)+SUM(missed3point)) AS ThreePointPerc FROM attendance LEFT JOIN playerstoteams ON attendance.playerid = playerstoteams.playerid WHERE playerstoteams.teamid =".$this->id;
            $row = $DBBasketball->query($statement)->fetch();
            return $row[0]*100;
        }
        
        public function GetLayupPercentage(){
            global $DBConnected, $DBBasketball;
            
            $statement = "SELECT SUM(madelayup)/(SUM(madelayup)+SUM(missedlayup)) AS LayupPerc FROM attendance LEFT JOIN playerstoteams ON attendance.playerid = playerstoteams.playerid WHERE playerstoteams.teamid =".$this->id;
            $row = $DBBasketball->query($statement)->fetch();
            return $row[0]*100;
        }
        
        public function GetRecommendedTrainingSession(){
            global $DBConnected, $DBBasketball;
            
            if ($this->Get2PointPercentage() < $this->Get3PointPercentage() && $this->Get2PointPercentage() < $this->GetLayupPercentage()){
                $smallest = 2;
            } else if ($this->Get3PointPercentage() < $this->Get2PointPercentage() && $this->Get3PointPercentage() < $this->GetLayupPercentage()){
                $smallest = 3;
            } else if ($this->GetLayupPercentage() < $this->Get3PointPercentage() && $this->GetLayupPercentage() < $this->Get2PointPercentage()){
                $smallest = 1;
            } else {
                $smallest = 0;
            }
            return $smallest;
        }
	
        public function removePlayers() {
		global $DBConnected, $DBBasketball;
		$bSuccess = false;
		if ($this->id > -1) {
			try {
			  if ($DBBasketball->query('DELETE FROM playerstoteams WHERE teamid='.$this->id)) {
          $bSuccess = true;
        }					
			} catch (Exception $e) {
				$bSuccess = false;
			}
		}
		return $bSuccess;
	}	
}

class Teams {
	private $_teamss = array();
	private $_isloaded = false;
	
	private function getTeamPlayers($Team) {
		global $DBConnected, $DBBasketball;
	  if ($DBConnected and (!$this->_isloaded)) {	
		  foreach($DBBasketball->query('SELECT players.name FROM ((teams LEFT JOIN playerstoteams '
                          . 'ON teams.id = playerstoteams.teamid) '
                          . 'LEFT JOIN players ON playerstoteams.playerid = players.id) WHERE teams.id='.$Team->id) as $row) {
				$Team->players[] = $row[0];
		  }  
		}
	}
        
        public function makeBestTeam(){
            global $DBConnected, $DBBasketball;
            $statement = "SELECT playerid, sum(made2point+made3point+madelayup) AS TOTALSHOTS FROM attendance GROUP BY attendance.playerid ORDER BY playerid";
            $row = $DBBasketball->query($statement)->fetch();
            return $row;
        }
        
        
	public function loadFromDatabase() {
		global $DBConnected, $DBBasketball;
		unset($this->_teams);
		$this->_teams = array();
		
		if ($DBConnected and (!$this->_isloaded)) {
		  foreach($DBBasketball->query('SELECT * FROM teams ORDER BY name') as $row) {
				$Team = new Team($row['id'], $row['name']);				
				$this->getTeamPlayers($Team);
				$this->_teams[] = $Team;
			};
			$this->_isloaded = true;
			return true;
		} else {
			return false;
		}
	}
	
	public function addTeam($Team) {
		global $DBConnected, $DBBasketball;
		$success = false;
		if ($DBConnected) {
			try {
			  if ($DBBasketball->query('INSERT INTO teams (name) VALUES ("'.$Team->name.'")')) {
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
	
	public function removeTeam($id) {
		global $DBConnected, $DBBasketball;
		$success = false;
		if ($DBConnected) {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			if ($DBBasketball->query('DELETE FROM teams WHERE ID='.$id)) {
				if ($DBBasketball->query('DELETE FROM playerstoteams WHERE teamid='.$id)) {
				  $success = true;
				}
			}
		}
		return $success;
	}
	
	public function editTeam($Team) {
		global $DBConnected, $DBBasketball;
		$success = false;
		if ($DBConnected) {
			try {
			  if ($DBBasketball->query('UPDATE  teams SET name="'.$Team->name.'" WHERE id='.$Team->id)) {
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
	
	public function getTeam($index) {
		return $this->_teams[$index];
	}
	
	public function findTeam($name) {
		$iCount = 0;
		$bFound = false;
		
		while (($iCount < count($this->_teams)) && (!$bFound)) {
			if ($this->_teams[$iCount]->name == $name) {
				$bFound = true;
			} else {
				$iCount++;
			}			
		}
		
		if ($bFound) {
			return $this->_teams[$iCount];
		} else {
			return null;
		}
	}
	
  public function findTeamByID($id) {
		$iCount = 0;
		$bFound = false;
		
		while (($iCount < count($this->_teams)) && (!$bFound)) {
			if ($this->_teams[$iCount]->id == $id) {
				$bFound = true;
			} else {
				$iCount++;
			}			
		}
		
		if ($bFound) {
			return $this->_teams[$iCount];
		} else {
			return null;
		}
	}
	
	public function getCount() {
		return count($this->_teams);
	}
        
        public function getAllTeamNames() {
            global $DBConnected, $DBBasketball;
            return $DBBasketball->query('SELECT name FROM teams');
        }
        
        
        
        
}	

?>