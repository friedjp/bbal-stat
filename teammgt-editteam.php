<!doctype html>
<HTML>
<HEAD>
  <?php
	  session_start();
		if (!isset($_SESSION['userid'])) {
		  header('Location: /index.php');
		  exit();
	  }
		if (!$_SESSION['isadmin']) {
		  header('Location: /home.php');
		  exit();
	  }		  
		
		// This page can be called in two ways
		//   - GET --> $id is the team ID to be edited. Present the user with the edit form with the existing team details filled in
		//     This will be done in the BODY section below
		//   - POST --> filled out form by the edit with the updated team details. This will be done in the HEAD section here as afterwards
		//     the user will be redirected to the main teammgt page as nothing will be shown here.
		
	  include "classes/teams.php";
		include "classes/players.php";
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['editteambtn'])))  {
		  $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
			$teamid = filter_var(trim($_POST['teamid']), FILTER_SANITIZE_NUMBER_INT);
      if ((empty($name)) || (!is_numeric($teamid))){
        header('Location: /teammgt.php');
		    exit();
			}
				
			$Teams = new Teams();
			$Teams->loadFromDatabase();
			$Team = $Teams->findTeamByID($teamid);
			if ($Team == null) {
			  header('Location: /teammgt.php');
		    exit();	
			}
				
			$Team->name = $name;
			$success = $Teams->editTeam($Team);
			if ($success) {
			  $Team->removePlayers();
				foreach ($_POST['players'] as $playerid) {
				  $playerid = filter_var($playerid, FILTER_SANITIZE_NUMBER_INT);	
					if (is_numeric($playerid)) {
  			    $Team->addPlayer($playerid);
				  }
				}
			}
							
			header('Location: /teammgt.php');		
		  exit();
		}
		
		include "head.php";
	?>
	
</HEAD>	
<BODY id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

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
        <li><a href="home.php">HOME</a></li>
				<li><a href="teammgt.php">TEAMS</a></li>
      </ul>
    </div>
  </div>
</nav>
				
<!-- Edit Container  -->
<div id="editteam" name="editteam" class="container-fluid">
  <div class="row">
    <div class="col-sm-8"> <!-- class="input-group" -->
      <h2>Team Management - Edit Team</h2><br>
			
 <?php
   if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		 $teamid = filter_var(trim($_GET['id']), FILTER_SANITIZE_NUMBER_INT);
		 if (!is_numeric($teamid)) {
			echo "<FONT COLOR=red>Invalid team. Press back.</FONT>"; 
		 } else {
			 $Teams = new Teams();
			 $Teams->loadFromDatabase();
			 $Team = $Teams->findTeamByID($teamid);
			 if ($Team == null) {
				 echo "<FONT COLOR=red>Invalid team. Press back.</FONT>";
			 } else {
         echo '			 
			    <form method=post action=teammgt-editteam.php>
            <div>
						  <input type=hidden name=teamid id=teamid value='.$Team->id.'>
              <label for="name">Name</label><input type="text" class="form-control" size="10" placeholder="Name" id=name name=name value="'.$Team->name.'" required>
					    <label for="players[]">Players</label><select class="form-control"  size=5 name="players[]" id="players[]" multiple="multiple"><option value="-1"></option>							
         ';
				 
				 $Players = New Players();
			   $Players->loadFromDatabase();
				 for ($iCount=0;$iCount < $Players->getCount(); $iCount++) {
				   $Player = $Players->getPlayer($iCount);
				   echo '<option value="'.$Player->id.'"';
				   if (in_array($Player->name, $Team->players)) {
					   echo " selected";
				   };
				   echo '>'.$Player->name.'</option>';
				 };
				 echo '
					 </select><BR><p>
			     <input type="submit" class="btn btn-danger" id="editteambtn" name="editteambtn" value="Save">
					 <input type="button" class="btn btn-danger" id="editteamcancelbtn" name="editteamcancelbtn" value="Cancel" onClick="window.location.href=\'teammgt.php\'">
           </div>
           </form>
			   ';
			 }
		 }
	 }
	 echo '
      </div>
		  </div>
	   </div>
   ';
?>

</BODY>
</HTML>