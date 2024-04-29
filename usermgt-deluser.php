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
    
	  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $id = filter_var(trim($_GET['id']), FILTER_SANITIZE_NUMBER_INT);
			if (!is_numeric($id)) {
		    header('Location: /usermgt.php?error=invalid_user_id');
        exit();
			}
			
  		$Users = new Users();
			// Cannot delete yourself
			if ($id == $_SESSION['userid']) {
		    header('Location: /usermgt.php?error=cannot_delete_yourself');
        exit();
      }

			$success = $Users->removeUser($id);
    };
    header('Location: /usermgt.php');		
		exit();
?>
</HEAD>
<BODY id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
</BODY>
</HTML>