<!doctype html>
<HTML>
<HEAD>
  <?php
	  session_start();
	  include "head.php";
	?>
</HEAD>
<BODY id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

<?php
  include 'classes/players.php';
	
	
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
	if (isset($_SESSION['userid']) && $_SESSION['isadmin']) {
		echo '
        <li><a href="usermgt.php">USER MGT</a></li>
		';
	};
	
	echo '
      </ul>
    </div>
  </div>
</nav>

<div class="jumbotron text-center">
  <h1>Basketball</h1> 
  <p>Basketball coach management tool</p> 
		<form method=post action=home.php>
    <div class="input-group">
      <input type="text" class="form-control" size="10" placeholder="Username" id=username name=username required>
			<input type="password" class="form-control" size="10" placeholder="Password" id=password name=password required> 	
			<input type="submit" class="btn btn-danger" id="loginrequest" name="loginrequest" value="Log in">
    </div>
  </form>
</div>


<!-- Container (About Section) -->
<div id="about" class="container-fluid">
  <div class="row">
    <div class="col-sm-8">
      <h2>Welcome!</h2><br>
      <h4>This basketball programs helps coaches to keep track of players, teams and relevant statistics.</h4><br>
';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if (isset($_GET['loginerror'])) {
    $errorcode = $_GET['loginerror'];
	  switch ($errorcode) {
			case 0 :
			  echo "<FONT COLOR=RED>General error logging in. Please try again later.</FONT>";
				break;
      case -1 :
		    echo "<FONT COLOR=RED>Invalid username.</FONT>";
  			break;
	  	case -2 :
		    echo "<FONT COLOR=RED>Invalid password.</FONT>";
			  break;
  		case -3 :
	  	  echo "<FONT COLOR=RED>Enter a username and password.</FONT>";
		  	break;
    }
	}
};	

echo '	
    </div>

  </div>
</div>

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