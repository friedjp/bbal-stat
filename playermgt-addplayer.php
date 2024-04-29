<!doctype html>
<HTML>
<HEAD>
  <?php
	  session_start();
		include "classes/players.php";
                include "classes/database.php";
		
		if (!isset($_SESSION['userid']) || (!isset($_SESSION['isadmin']))) {
		  header('Location: /playermgt.php');
		   exit();
	  }
		if (!$_SESSION['isadmin']) {
		  header('Location: /playermgt.php');
		  exit();
	  }		
    
	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		  if (isset($_POST['addplayerbtn'])) {
        $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
        $dob = filter_var(trim($_POST['dob']), FILTER_SANITIZE_STRING);	
				$playerstyle = filter_var(trim($_POST['playerstyle']), FILTER_SANITIZE_STRING);	
				$isinjured = 0;
				if (isset($_POST['isinjured'])) {
					if (($_POST['isinjured']) == "on") {
						$isinjured = 1;
					}
				}
                        if (empty($name)) {
                            /*header('Location: /playermgt.php');
                        /* exit();*/
			 }
                                echo 'wraf0';
  			$Players = new Players();
                        echo 'wraf1';
				$Player = new Player(100, $name);
                                echo 'wraf2 <br>';
				$Player->dob = $dob;
                                echo 'wraf3<br>';
				$Player->playerstyle = $playerstyle;
                                echo 'wraf4<br>';
				$Player->isinjured = $isinjured;
				 $Players->addPlayer($Player); 
                                
               		}
                        
    };
    header('Location: /playermgt.php');		
		exit(); 
?>
</HEAD>
<BODY id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
</BODY>
</HTML>