<?php
        session_start();
		if (!isset($_SESSION['userid'])) {
		  header('Location: /index.php');
		  exit();
	  }
	  include "head.php";
		include "classes/players.php";
                include "classes/attendance.php";
                
                
              echo '
	<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#myPage">&nbsp;</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="/playermgt.php">BACK TO PLAYERS</a></li>
			
      </ul>
    </div>
  </div>
</nav> <br> <br>'
                ;
                
                
                
             $PlayerID = $_GET['id'];   
             $Player = New Player($PlayerID, " ");
             
  
             

            echo ' <table class="table">
	<tr>
           <td class="h4" style="color:white;background-color:#4d0e00">Player ID</td>
	  <td class="h4" style="color:white;background-color:#4d0e00">2 Point Percentage</td>
		<td class="h4" style="color:white;background-color:#4d0e00">3 Point Percentage</td>
		<td class="h4" style="color:white;background-color:#4d0e00">Layup Percentage</td>
		</tr> 
		  
                   <tr>
		  <td class="table-data">'.$Player->id.'</td>
			<td class="table-data">'.$Player->get2PointPercentage().'%</td>
			<td class="table-data">'.$Player->get3PointPercentage().'%</td>
			<td class="table-data">'.$Player->getLayupPercentage().'%</td>
			</tr>
		';
		
	echo '</TABLE>';
        
        $Attendances = New Attendances();
        $Attendances->loadFromDatabase();
        $datum = date('y-m-d h:i:s');
        
        
        
       
        echo ' <table class="table">
        <tr>
        <td class="h4" style="color:white;background-color:#4d0e00">Date</td>
        <td class="h4" style="color:white;background-color:#4d0e00">Missed 3 Point</td>
        <td class="h4" style="color:white;background-color:#4d0e00">Made 3 Point</td>
        <td class="h4" style="color:white;background-color:#4d0e00">Missed 2 Point</td>
        <td class="h4" style="color:white;background-color:#4d0e00">Made 2 Point</td>
        <td class="h4" style="color:white;background-color:#4d0e00">Missed Layup</td>
        <td class="h4" style="color:white;background-color:#4d0e00">Made Layup</td>
        </tr>';
        
        for ($iCount=0;$iCount < $Attendances->getCount();$iCount++) {
            $Attendance = $Attendances->getPlayer($iCount);
            if($Attendance->playerid == $PlayerID){
                echo 
		  
                   '<tr>
		  <td class="table-data">'.$Attendance->datum.'</td>
                  <td class="table-data">'.$Attendance->missed3point.'</td>
                  <td class="table-data">'.$Attendance->made3point.'</td>
                   <td class="table-data">'.$Attendance->missed2point.'</td>
                    <td class="table-data">'.$Attendance->made3point.'</td>
                  <td class="table-data">'.$Attendance->missedlayup.'</td>
                 <td class="table-data">'.$Attendance->madelayup.'</td>
                                          
                </tr>
		';
            } else {
                
            }
        }	
            
        
        echo '</TABLE>';
?>