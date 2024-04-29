<!doctype html>
<HTML>
<HEAD>
  <?php
	  session_start();
		include "classes/teams.php";
		
		if (!isset($_SESSION['userid']) || (!isset($_SESSION['isadmin']))) {
		  header('Location: /teammgt.php');
		  exit();
	  }
		if (!$_SESSION['isadmin']) {
		  header('Location: /teammgt.php');
		  exit();
	  }		
    
	  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $id = filter_var(trim($_GET['id']), FILTER_SANITIZE_STRING);
  		$Teams = new Teams();
			$success = $Teams->removeTeam($id);
    };
    header('Location: /teammgt.php');		
		exit();
?>
</HEAD>
<BODY id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
</BODY>
</HTML>