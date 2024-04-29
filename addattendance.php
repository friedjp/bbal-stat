<!doctype html>
<HTML>
<HEAD>
  <?php
  
	  session_start();
		include "classes/players.php";
                include "classes/attendance.php";
                $Players = New Players();
                
                $Players->loadFromDatabase();
                
                $Attendances = New Attendances();
                $Attendances->loadFromDatabase();
                date_default_timezone_set('Asia/Singapore');
                $datum = date('y-m-d h:i:s');
                
                for ($iCount=0;$iCount < $Players->getCount();$iCount++) {
                    $Player = $Players->getPlayer($iCount);
                    if(isset($_POST['yesno'.$Player->id]) ) {
                        $present = 0;
                        $missed2pointers =0;
                        $made2pointers =0;
                        $missed3pointers =0;
                        $made3pointers =0;
                        $missedlayups =0;
                        $madelayups =0;
                    
                        if ($_POST['yesno'.$Player->id] == "Yes"){
                            $present = 1;
                            $missed2pointers = $_POST['missed2pointer'.$Player->id];
                            $made2pointers = $_POST['made2pointer'.$Player->id];
                            $missed3pointers = $_POST['missed3pointer'.$Player->id];
                            $made3pointers = $_POST['made3pointer'.$Player->id];
                            $missedlayups = $_POST['missedlayup'.$Player->id];
                            $madelayups = $_POST['madelayup'.$Player->id];
                        }      
                        
                        $Attendance = new Attendance($Player->id,$present,$missed2pointers,$made2pointers,$missed3pointers,$made3pointers,$missedlayups,$madelayups,$datum);

                        $success = $Attendances->addAttendance($Attendance);
                        echo $success;
                
                    }
                    
               
             }
             header('Location: /home.php');
		  exit();
             
                
                
                               
                
                
                
                
                
                
                
                
                
                
                
                
             
                            
