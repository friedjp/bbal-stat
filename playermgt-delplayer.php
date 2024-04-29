<HTML>
<HEAD>

<?php

            
	  session_start();
          include "classes/teams.php";
          include "classes/players.php";
		if (!isset($_SESSION['userid'])) {
		  header('Location: /index.php');
		  exit();
	  }
		if (!$_SESSION['isadmin']) {
		  header('Location: /home.php');
		  exit();
	  }
          
          $playerid = filter_var(trim($_GET['id']), FILTER_SANITIZE_NUMBER_INT);
          $Players = new Players();
	  $PlayerDeleted = $Players->removePlayer($playerid);
          header('Location: /playermgt.php');
          
          	
          //if ($PlayerFound == false) {
          //   echo "<FONT COLOR=red>Invalid player. Press back.</FONT>";
	  //} else {
          //    echo 'yes it works :)';
          //}