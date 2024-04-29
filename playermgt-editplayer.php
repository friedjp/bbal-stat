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
		//   - GET --> $id is the team ID to be edited. Present the user with the edit form with the existing player details filled in
		//     This will be done in the BODY section below
		//   - POST --> filled out form by the edit with the updated player details. This will be done in the HEAD section here as afterwards
		//     the user will be redirected to the main playermgt page as nothing will be shown here.
		
	  include "classes/teams.php";
		include "classes/players.php";
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['editplayerbtn'])))  {
		  $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
			$dob = filter_var(trim($_POST['dob']), FILTER_SANITIZE_STRING);
			$playerstyle = filter_var(trim($_POST['playerstyle']), FILTER_SANITIZE_STRING);
			$playerid = filter_var(trim($_POST['playerid']), FILTER_SANITIZE_NUMBER_INT);
			$injured = 0;
		  if (isset($_POST['isinjured'])) {
			  if (($_POST['isinjured']) == "on") {
					$injured = 1;
				}
			}
			
      if ((empty($name)) || (!is_numeric($playerid))){
        header('Location: /playermgt.php');
		    exit();
			}
				
			$Players = new Players();
			$Players->loadFromDatabase();
			$Player = $Players->findPlayerByID($playerid);
			if ($Player == null) {
			  header('Location: /playermgt.php');
		    exit();	
			}
				
			$Player->name = $name;
			$Player->dob = $dob;
			$Player->playerstyle = $playerstyle;
			$Player->injured = $injured;
			$success = $Players->editPlayer($Player);
			if ($success) {
			  $Player->removeTeams();
				foreach ($_POST['teams'] as $teamid) {
				  $teamid = filter_var($teamid, FILTER_SANITIZE_NUMBER_INT);	
					echo "<BR>Adding Player to Team ".$teamid;
					if (is_numeric($teamid)) {
  			    $Player->addTeam($teamid);
				  }
				}
			}
							
			header('Location: /playermgt.php');		
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
				<li><a href="playermgt.php">PLAYERS</a></li>
      </ul>
    </div>
  </div>
</nav>
				
<!-- Edit Container  -->
<div id="editplayer" name="editplayer" class="container-fluid">
  <div class="row">
    <div class="col-sm-8"> <!-- class="input-group" -->
      <h2>Player Management - Edit Player</h2><br>
			
 <?php
   if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		 $playerid = filter_var(trim($_GET['id']), FILTER_SANITIZE_NUMBER_INT);
		 if (!is_numeric($playerid)) {
			echo "<FONT COLOR=red>Invalid player. Press back.</FONT>"; 
		 } else {
			 $Players = new Players();
			 $Players->loadFromDatabase();
			 $Player = $Players->findPlayerByID($playerid);
			 if ($Player == null) {
				 echo "<FONT COLOR=red>Invalid player. Press back.</FONT>";
			 } else {
         echo '			 
			    <form method=post action=playermgt-editplayer.php>
            <div>
						  <input type=hidden name=playerid id=playerid value='.$Player->id.'>
              <label for="name">Name</label><input type="text" class="form-control" size="10" placeholder="Name" id=name name=name value="'.$Player->name.'" required>
              <label for="dob">Date of birth</label><input type="date" class="form-control" size="10" placeholder="dob" id=dob name=dob value='.$Player->dob.' required> 
              <label for="playerstyle">Player style</label>
					    <input class="form-control" id=playerstyle name=playerstyle list=playerstylelist value="'.$Player->playerstyle.'">
					      <datalist id=playerstylelist>
						    <option value="">
					';
					global $PLAYERSTYLES;	
					foreach ($PLAYERSTYLES as $stylevalue) {
						echo '<option value="'.$stylevalue.'">';
					};
					echo '
					  </datalist>		
							<label for="isinjured">Injured</label><input type="checkbox" class="form-control" size="10" id=isinjured name=isinjured
					';
					if ($Player->injured) {
						echo ' checked';
					};
					echo '>
					    <label for="teams[]">Teams</label><select class="form-control"  size=5 name="teams[]" id="teams[]" multiple="multiple"><option value="-1"></option>							
         ';
				 
				 $Teams = New Teams();
			   $Teams->loadFromDatabase();
				 for ($iCount=0;$iCount < $Teams->getCount(); $iCount++) {
				   $Team = $Teams->getTeam($iCount);
				   echo '<option value="'.$Team->id.'"';
				   if (in_array($Player->name, $Team->players)) {
					   echo " selected";
				   };
				   echo '>'.$Team->name.'</option>';
				 };
				 echo '
					 </select><BR><p>
			     <input type="submit" class="btn btn-danger" id="editplayerbtn" name="editplayerbtn" value="Save">
					 <input type="button" class="btn btn-danger" id="editplayercancelbtn" name="editplayerancelbtn" value="Cancel" onClick="window.location.href=\'playermgt.php\'">
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