<!doctype html>
<HTML>
<HEAD>
  <?php
	  session_start();
		if (!isset($_SESSION['userid']) || (!isset($_SESSION['isadmin']))) {
		  header('Location: /index.php');
		  exit();
	  }
		if (!$_SESSION['isadmin']) {
		  header('Location: /index.php');
		  exit();
	  }	
		
	  include "head.php";
		include "classes/usermanagement.php";
	?>
	
	<script>
	  function AddUser() {
			document.getElementById('adduser').style.visibility = "visible";
			document.getElementById('adduser').style.display = "block";
			document.getElementById('userlist').style.visibility = "hidden";
			document.getElementById('userlist').style.display = "none";
	  }
		
		function CancelAddUser() {
			document.getElementById('userlist').style.visibility = "visible";
			document.getElementById('userlist').style.display = "block";
			document.getElementById('adduser').style.visibility = "hidden";
			document.getElementById('adduser').style.display = "none";
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
        <li><a href="Javascript:AddUser()">ADD USER</a></li>

      </ul>
    </div>
  </div>
</nav>


<!-- Container  -->
<div id="userlist" name="userlist" class="container-fluid">
  <div class="row">
    <div class="col-sm-8">
      <h2>User Management</h2><br>
			<table class="table">
		  <tr><td class="h4" style="color:white;background-color:#4d0e00">Delete</td><td class="h4" style="color:white;background-color:#4d0e00">Username</td><td class="h4" style="color:white;background-color:#4d0e00">Is Admin</td></tr>
	';
	
	$Users = New Users();
	$Users->loadFromDatabase();
	for ($iCount=0;$iCount < $Users->getCount();$iCount++) {
		$User = $Users->getUser($iCount);
		$isadminTxt = $User->isadmin ? "Yes" : "No";
		echo "<tr><td class='table-data'><A HREF='usermgt-deluser.php?id=".$User->id."'><img src=images/trashcan-16x16.png></A></td><td class='table-data'>".$User->username."</td><td class='table-data'>".$isadminTxt."</td></tr>";
	};
	echo '</TABLE>
    </div>
  </div>
</div>

<div id="adduser" name="adduser" class="container-fluid" style="visibility:hidden">
  <div class="row">
    <div class="col-sm-8"> <!-- class="input-group" -->
      <h2>User Management - Add User</h2><br>
			<form method=post action=usermgt-adduser.php>
        <div>
          <label for="username">Username</label><input type="text" class="form-control" size="10" placeholder="Username" id=username name=username required>
			    <label for="password">Password</label><input type="password" class="form-control" size="10" placeholder="Password" id=password name=password required> 
          <label for="isadmin">Is Administrator</label><input type="checkbox" class="form-control" size="1" id=isadmin name=isadmin> 					
			    <input type="submit" class="btn btn-danger" id="adduserbtn" name="adduserbtn" value="Add User">
					<input type="button" class="btn btn-danger" id="addusercancelbtn" name="addusercancelbtn" value="Cancel" onClick="CancelAddUser()">
        </div>
      </form>
    </div>
  </div>
</div>

	';
	

	
	
?>

</BODY>
</HTML>