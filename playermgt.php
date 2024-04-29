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
		include "classes/players.php";
	?>
	
	<script>
	  function AddPlayer() {
			document.getElementById('addplayer').style.visibility = "visible";
			document.getElementById('addplayer').style.display = "block";
			document.getElementById('playerlist').style.visibility = "hidden";
			document.getElementById('playerlist').style.display = "none";
	  }
		
		function CancelAddPlayer() {
			document.getElementById('playerlist').style.visibility = "visible";
			document.getElementById('playerlist').style.display = "block";
			document.getElementById('addplayer').style.visibility = "hidden";
			document.getElementById('addplayer').style.display = "none";
	  }
	</script>
</HEAD>	
<BODY id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

<?php
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
        <li><a href="Javascript:AddPlayer()">ADD PLAYER</a></li>
			';
		}
		echo '
      </ul>
    </div>
  </div>
</nav>


<!-- Container  -->
<div id="playerlist" name="playerlist" class="container-fluid">
  <div class="row">
    <div class="col-sm-8">
      <h2>Player Management</h2><br>
			<table class="table">
	';

  if ($_SESSION['isadmin']) {
	  echo '
		  <tr><td class="h4" style="color:white;background-color:#4d0e00;width=40">Delete</td>
		  <td class="h4" style="color:white;background-color:#4d0e00;width=40">Edit</td>';
	}
	
	echo '
	  <td class="h4" style="color:white;background-color:#4d0e00">Player name</td>
          <td class="h4" style="color:white;background-color:#4d0e00">Player Number</td>
		<td class="h4" style="color:white;background-color:#4d0e00">Date of birth</td>
		<td class="h4" style="color:white;background-color:#4d0e00">Style</td>
		<td class="h4" style="color:white;background-color:#4d0e00">Injured</td>
		<td class="h4" style="color:white;background-color:#4d0e00">Teams</td>
                <td class="h4" style="color:white;background-color:#4d0e00">Average Shot Percentage</td>
                <td class="h4" style="color:white;background-color:#4d0e00">Attendance Rate</td>
		</tr>
	';
	
	$Players = New Players();
	$Players->loadFromDatabase();
	for ($iCount=0;$iCount < $Players->getCount();$iCount++) {
		$Player = $Players->getPlayer($iCount);
		$isinjuredTxt = $Player->injured ? "Yes" : "No";
		
    if ($_SESSION['isadmin']) {
	    echo '
		    <tr><td class="table-data"><A HREF="playermgt-delplayer.php?id='.$Player->id.'"><img src=images/trashcan-16x16.png></A></td>
			  <td class="table-data"><A HREF="playermgt-editplayer.php?id='.$Player->id.'"><img src=images/edit-16x16.png></A></td>
		  ';
	  };
		echo '
		  <td class="table-data">'.$Player->name.'</td>
                      <td class="table-data">'.$Player->id.'</td>
			<td class="table-data">'.$Player->dob.'</td>
			<td class="table-data">'.$Player->playerstyle.'</td>
			<td class="table-data">'.$isinjuredTxt.'</td>
			<td class="table-data">'.implode(",", $Player->teams).'</td>
                            <td class="table-data">'.$Player->getAvgPercentage().'% <a href="/individualplayerstats.php?id='.$Player->id.'">See More</a>'.'</td>
                                <td class="table-data">'.$Player->getAttendanceRate().'%</td>
			</tr>
		';
                //$Players->editShootingAttenPlayer($Player);
                
	};	
	echo '</TABLE>
    </div>
  </div>
</div>

<div id="addplayer" name="addplayer" class="container-fluid" style="visibility:hidden">
  <div class="row">
    <div class="col-sm-8"> <!-- class="input-group" -->
      <h2>Player Management - Add Player</h2><br>
			<form method=post action=playermgt-addplayer.php>
        <div>
          <label for="name">Name</label><input type="text" class="form-control" size="10" placeholder="Name" id=name name=name required>
			    <label for="dob">Date of birth</label><input type="date" class="form-control" size="10" placeholder="dob" id=dob name=dob required> 
          <label for="playerstyle">Player style</label>
					<input class="form-control" id=playerstyle name=playerstyle list=playerstylelist>
					  <datalist id=playerstylelist>
						<option value="">
					';
					global $PLAYERSTYLES;	
					foreach ($PLAYERSTYLES as $stylevalue) {
						echo '<option value="'.$stylevalue.'">';
					};
					echo '
					  </datalist>
					<!-- <input type="combobox" class="form-control" size="10" id=playerstyle name=playserstyle> -->
					<label for="isinjured">Injured</label><input type="checkbox" class="form-control" size="10" id=isinjured name=isinjured> 					 					
			    <input type="submit" class="btn btn-danger" id="addplayerbtn" name="addplayerbtn" value="Add Player">
					<input type="button" class="btn btn-danger" id="addplayercancelbtn" name="addplayercancelbtn" value="Cancel" onClick="CancelAddPlayer()">
        </div>
      </form>
    </div>
  </div>
</div>

	';
	

	
	
?>

</BODY>
</HTML>