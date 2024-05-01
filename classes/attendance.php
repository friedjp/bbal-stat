<?php

include 'database.php';

class Attendance {
	public int $playerid;
	public int $presentsession;
        public int $missed2point;
        public int $made2point;
        public int $missed3point;
        public int $made3point;
        public int $missedlayup;
        public int $madelayup;
        public $datum;
        
	
	function __construct($playerid, $presentsession, $missed2point,$made2point ,$missed3point ,$made3point ,$missedlayup , $madelayup, $datum) {
		$this->playerid = $playerid;
                $this->presentsession = $presentsession;
                $this->missed2point = $missed2point;
                $this->made2point = $made2point;
                $this->missed3point = $missed3point;
                $this->made3point = $made3point;  
                $this->missedlayup = $missedlayup;
                $this->madelayup = $madelayup;  
                $this->datum = $datum;
	}
}	

class Attendances {
	private $_attendances = array();
	private $_isloaded = false;
	
	
	public function loadFromDatabase() {
		global $DBConnected, $DBBasketball;
		unset($this->_attendances);
		$this->_attendances = array();
		
		if ($DBConnected and (!$this->_isloaded)) {
		  foreach($DBBasketball->query('SELECT * FROM attendance ORDER BY datum,playerid') as $row) {
				$Attendance = new Attendance($row['playerid'], 
                                        $row['presentsession'] , 
                                        $row['missed2point'], 
                                        $row['made2point'], 
                                        $row['missed3point'], 
                                        $row['made3point'], 
                                        $row['missedlayup'], 
                                        $row['madelayup'], 
                                        $row['datum'],  
                                        );
				
				$this->_attendances[] = $Attendance;
			};
			$this->_isloaded = true;
			return true;
		} else {
			return false;
		}
	}
        
	
	public function addAttendance($Attendance) {
		global $DBConnected, $DBBasketball;
		$success = false;
		if ($DBConnected) {
			// Insert
			try {
                          $statement = "INSERT INTO `attendance`(`playerid`, `presentsession`, `missed2point`, `made2point`, `missed3point`, `made3point`, `missedlayup`, `madelayup`, `datum`) VALUES ($Attendance->playerid,$Attendance->presentsession,$Attendance->missed2point,$Attendance->made2point,$Attendance->missed3point,$Attendance->made3point,$Attendance->missedlayup,$Attendance->madelayup,'$Attendance->datum')";;  
			  if ($DBBasketball->query($statement)) {
                            $this->_isloaded = false;
			    $this->loadFromDatabase();
				  $success = true;
			  }
			}	
                        catch (Exception $e) {
                            $success = false;
                        }		
                }
		return $success;
	}
        
        
        public function getPlayer($index) {
		return $this->_attendances[$index];
	}
        
        public function getCount() {
		return count($this->_attendances);
    }
	
	

}