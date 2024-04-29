<!doctype html>
<HTML>
<HEAD>
  <?php
	  session_start();
		include "classes/teams.php";
		
		if (!isset($_SESSION['userid']) || (!isset($_SESSION['isadmin']))) {
		  header('Location: /index.php');
		  exit();
	  }
		if (!$_SESSION['isadmin']) {
		  header('Location: /index.php');
		  exit();
	  }		
    
	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		  if (isset($_POST['addteambtn'])) {
        $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
        if (empty($name)) {
          header('Location: /teammgt.php');
		      exit();
			  }

				// First add the team itself
  			$Teams = new Teams();
				$Team =  new Team(-1, $name);
				$success = $Teams->addTeam($Team);
				
				if ($success) {				
				  // we need to know the team ID 
				  $Team = $Teams->findTeam($name);
				  if ($Team != null) {				
				    // now we can add the players to the newly created team
					  foreach ($_POST['players'] as $playerid) {
							$playerid = filter_var($playerid, FILTER_SANITIZE_NUMBER_INT);	
							if (is_numeric($playerid)) {
  						  $Team->addPlayer($playerid);
							}
					  }
				  }
				}
				
  		}
    };
    header('Location: /teammgt.php');		
	  exit();
  ?>
</HEAD>
<BODY id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
</BODY>
</HTML>