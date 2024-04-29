<!doctype html>
<HTML>
<HEAD>
  <?php
	  session_start();
    include "classes/usermanagement.php";
	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
		  if (isset($_POST['loginrequest'])) {
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);	
        if (empty($username) || empty($password)) {
          header('Location: /index.php?loginerror=-3');
		      exit();
			  }
			
  			$Users = new Users();
	  		$loginresult = explode("~", $Users->loginUser($username, $password));
		  	$errorcode = $loginresult[0];
			  $userid = $loginresult[1];
  			$isadmin = $loginresult[2];
			
	  		if ($errorcode != 1) {
		  		header('Location: /index.php?loginerror='.$errorcode);
		      exit();
  			}
			
	  		$_SESSION['userid'] = $userid;
		  	$_SESSION['isadmin'] = $isadmin;
  		}
    };
		
		if (!isset($_SESSION['userid'])) {
		  header('Location: /index.php');
	  	exit();
	  };
		
	  include "head.php";
          
          
          
?>
</HEAD>
<BODY id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
    
    

<?php
    
 $Username = $username;
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
	';
	
         echo '<h2>Welcome '.$Username.'!</h2>';
        
        
	if (isset($_SESSION['userid']) && $_SESSION['isadmin']) {
		echo '<li><a href="usermgt.php">USER MGT</a></li>';
                		echo '<li><a href="attendance.php">ATTENDANCE</a></li>';

	};
	if (isset($_SESSION['userid'])) {
		echo '<li><a href="playermgt.php">PLAYERS</a></li>';
		echo '<li><a href="teammgt.php">TEAMS</a></li>';
		echo '<li><a href="logout.php">&nbsp;|&nbsp;LOGOUT</a></li>';
	};
  echo '	
      </ul>
    </div>
  </div>
</nav>

	';

	
	/*
	$Players = new Players();
	$Players->loadFromDatabase();
	echo "Number of Players ".$Players->getCount()."<BR>";
	
	for ($iCount=0;$iCount < $Players->getCount();$iCount++) {
		$Player = $Players->getPlayer($iCount);
		echo "Player ".$iCount.": ID=".$Player->id." Name=".$Player->name."<BR>";
	}
	
	echo "Creating new Player<BR>";
	$Player = new Player(-1, "Test1");
	$Player->dob = "2022-07-18";
	$Player->injured = false;
	$Player->playerstyle = "Sucks";
	$Players->addPlayer($Player);
	*/
	
	/*
	echo "Removing existing Player<BR>";
	$Players->removePlayer(15);
	*/
	
	
?>

</BODY>

</HTML>