<!doctype html>
<HTML>
<HEAD>
  <?php
	  session_start();
		include "classes/usermanagement.php";
		
		if (!isset($_SESSION['userid']) || (!isset($_SESSION['isadmin']))) {
		  header('Location: /index.php');
		  exit();
	  }
		if (!$_SESSION['isadmin']) {
		  header('Location: /index.php');
		  exit();
	  }		
    
	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		  if (isset($_POST['adduserbtn'])) {
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);	
				$isadmin = 0;
				if (isset($_POST['isadmin'])) {
					if (($_POST['isadmin']) == "on") {
						$isadmin = 1;
					}
				}
        if (empty($username) || empty($password)) {
          header('Location: /usermgt.php');
		      exit();
			  }

  			$Users = new Users();
				$success = $Users->addUser($username, $password, $isadmin);
  		}
    };
    header('Location: /usermgt.php');		
		exit();
?>
</HEAD>
<BODY id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
</BODY>
</HTML>