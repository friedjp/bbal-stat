<!doctype html>
<HTML>
<HEAD>
  <?php
	  session_start();
		if (!isset($_SESSION['userid'])) {
		  header('Location: /index.php');
		  exit();
	  }
	  include "head.php";
		include "classes/teams.php";
		include "classes/players.php";
	?>
	
	<script>
	  function AddTeam() {
			document.getElementById('addteam').style.visibility = "visible";
			document.getElementById('addteam').style.display = "block";
			document.getElementById('teamlist').style.visibility = "hidden";
			document.getElementById('teamlist').style.display = "none";
	  }
		
		function CancelAddPlayer() {
			document.getElementById('teamlist').style.visibility = "visible";
			document.getElementById('teamlist').style.display = "block";
			document.getElementById('addteam').style.visibility = "hidden";
			document.getElementById('addteam').style.display = "none";
	  }
	</script>
</HEAD>	
<BODY id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

<?php
$PLAYERSTYLES = array("Team Trash", "Layup Drill", "2 Pointer Drill", "3 Pointer Drill",);

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
        <li><a href="home.php">HOME</a></li>
		';
		if ($_SESSION['isadmin']) {
			echo '
        <li><a href="Javascript:AddTeam()">ADD TEAM</a></li>
			';
		}
		echo '
      </ul>
    </div>
  </div>
</nav>


<!-- Container  -->
<div id="teamlist" name="teamlist" class="container-fluid">
  <div class="row">
    <div class="col-sm-8">
      <h2>Team Management</h2><br>
			<table class="table">
	';

  if ($_SESSION['isadmin']) {
	  echo '
		  <tr><td class="h4" style="color:white;background-color:#4d0e00;width=40">Delete</td>
		  <td class="h4" style="color:white;background-color:#4d0e00;width=40">Edit</td>';
	}
	
	echo '
	  <td class="h4" style="color:white;background-color:#4d0e00">Team name</td>
		<td class="h4" style="color:white;background-color:#4d0e00">Players</td>
                <td class="h4" style="color:white;background-color:#4d0e00">Average Team Shooting Percentage</td>
                <td class="h4" style="color:white;background-color:#4d0e00">3 Point Team Shooting Percentage</td>
                <td class="h4" style="color:white;background-color:#4d0e00">2 Point Team Shooting Percentage</td>
                <td class="h4" style="color:white;background-color:#4d0e00">Layup Team Shooting Percentage</td>
                <td class="h4" style="color:white;background-color:#4d0e00">Recommended Training Session</td>
		</tr>
	';
	
	$Teams = New Teams();
	$Teams->loadFromDatabase();
        $BestTeam = $Teams->makeBestTeam();
        
	for ($iCount=0;$iCount < $Teams->getCount();$iCount++) {
		$Team = $Teams->getTeam($iCount);
		
    if ($_SESSION['isadmin']) {
	    echo '
		    <tr><td class="table-data"><A HREF="teammgt-delteam.php?id='.$Team->id.'"><img src=images/trashcan-16x16.png></A></td>
			  <td class="table-data"><A HREF="teammgt-editteam.php?id='.$Team->id.'"><img src=images/edit-16x16.png></A></td>
                              
		  ';
	  };
		echo '
		  <td class="table-data">'.$Team->name.'</td>
			<td class="table-data">'.implode(",", $Team->players).'</td>
                            <td class="table-data">'.$Team->GetAvgPercentage().'% <a href="/individualplayerstats.php?id= " </td>
                                  <td class="table-data">'.$Team->Get3PointPercentage().'% </td>
                                      <td class="table-data">'.$Team->Get2PointPercentage().'% </td>
                                          <td class="table-data">'.$Team->GetLayupPercentage().'% </td>
                                          <td class="table-data">'.$PLAYERSTYLES[$Team->GetRecommendedTrainingSession()].' </td>    
			</tr>
		';
	};	
        echo '<br>';
        
        
        
	echo '</TABLE>
            
        


    </div>
  </div>
</div>

<div id="addteam" name="addteam" class="container-fluid" style="visibility:hidden">
  <div class="row">
    <div class="col-sm-8"> <!-- class="input-group" -->
      <h2>Team Management - Add Team</h2><br>
			<form method=post action=teammgt-addteam.php>
        <div>
          <label for="name">Name</label><input type="text" class="form-control" size="10" placeholder="Name" id=name name=name required>
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
					</select><BR><p>&nbsp;<p>
			    <input type="submit" class="btn btn-danger" id="addteambtn" name="addteambtn" value="Add Team">
					<input type="button" class="btn btn-danger" id="addteamcancelbtn" name="addteamcancelbtn" value="Cancel" onClick="CancelAddPlayer()">
        </div>
      </form>
    </div>
  </div>
</div>

	';	
?>

</BODY>
</HTML>