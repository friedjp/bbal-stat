
<?php
session_start();
	  if (!isset($_SESSION['userid'])) {
		  header('Location: /index.php');
		  exit();
	  }
	  include "head.php";
          include "classes/players.php";
          include "classes/teams.php";
$YESNO = array("Yes", "No");
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
		echo '
      </ul>
    </div>
  </div>
</nav>';




echo '
<!-- Container  -->
<div id="playerlist" name="playerlist" class="container-fluid">
  <div class="row">
    <div class="col-sm-8">
      <h2>Take Attendance</h2><br>
			<table class="table">
	';



	
	echo '
	  <td class="h4" style="color:white;background-color:#4d0e00">Player name</td>
		<td class="h4" style="color:white;background-color:#4d0e00">Injured</td>
		<td class="h4" style="color:white;background-color:#4d0e00">Teams</td>
                <td class="h4" style="color:white;background-color:#4d0e00">Present</td>
                <td class="h4" style="color:white;background-color:#4d0e00">Missed 2 Pointers</td>
                <td class="h4" style="color:white;background-color:#4d0e00">Made 2 Pointers</td>
                <td class="h4" style="color:white;background-color:#4d0e00">Missed 3 Pointers</td>
                <td class="h4" style="color:white;background-color:#4d0e00">Made 3 Pointers</td>
                <td class="h4" style="color:white;background-color:#4d0e00">Missed Layups </td>
                <td class="h4" style="color:white;background-color:#4d0e00">Made Layups </td>
		</tr>
	';
	
	$Players = New Players();
	$Players->loadFromDatabase();
	for ($iCount=0;$iCount < $Players->getCount();$iCount++) {
		$Player = $Players->getPlayer($iCount);
		$isinjuredTxt = $Player->injured ? "Yes" : "No";
		
 
		echo '
            <form method=post action=addattendance.php>
		  <td class="table-data">'.$Player->name.'</td>  <input type="hidden" class="form-control" size="10" placeholder="Name" id=name '
                        . 'value='.$Player->name.' required>
			<td class="table-data">'.$isinjuredTxt.'</td>
			<td class="table-data">'.implode(",", $Player->teams).'</td>
                       
                        <td> <input class="form-control" id=yesno'.$Player->id.' name=yesno'.$Player->id.' list=yesnolist>
					  <datalist id=yesnolist>
						<option value="">
					';
					global $YESNO;	
					foreach ($YESNO as $stylevalue) {
						echo '<option value="'.$stylevalue.'">';
					};
					echo '
					  </datalist>
					<!-- <input type="combobox" class="form-control" size="10" id=yesno name=yesno> -->
                                    </td>
                        <td> <input type="text" id=missed2pointer'.$Player->id.' name=missed2pointer'.$Player->id.'> </td>
                        <td> <input type="text" id=made2pointer'.$Player->id.' name=made2pointer'.$Player->id.'> </td>
                        <td> <input type="text" id=missed3pointer'.$Player->id.' name=missed3pointer'.$Player->id.'> </td>
                        <td> <input type="text" id=made3pointer'.$Player->id.' name=made3pointer'.$Player->id.'> </td>
                        <td> <input type="text" id=missedlayup'.$Player->id.' name=missedlayup'.$Player->id.'> </td>
                        <td> <input type="text" id=madelayup'.$Player->id.' name=madelayup'.$Player->id.'> </td>
			</tr>
		';
	};
        echo'</TABLE>
        <input type="submit" class="btn btn-danger" id="addattendance" name="addattendance" value="Submit Attendance">
        </form>
    </div>
  </div>
</div>';
	
	

                                        
                                        
	      ?>
	

</BODY>
</HTML>
                                  
