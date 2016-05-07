<?php
//Get all sql related connections
$servername = "localhost";
$username = "root";
$password = "pallavi";
$database ="chatSystem";
$incorrectAuthentication=false;
$incorrectUser=false;
$incorrectPsswd=false;
$isLogged="";

//function to establish connection that can be used by post.php
function get_connection() {
	$connection = mysql_connect('localhost', 'root', 'pallavi');
	mysql_select_db('chatSystem', $conn);
	return $connection;
}

if (!$conn = mysql_connect($servername, $username, $password)) {
	echo 'Could not connect to mysql';
	exit;
}
if (!mysql_select_db('chatSystem', $conn)) {
	echo 'Could not select database';
	exit;
}

session_start(); //starting a session
$_SESSION['connection']=$conn;
//on logout writing that user exited the session and updating same in database
if(isset($_GET['logout'])){
	$fp = fopen($_GET['logfile'].".html", 'a');
	fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['name'] ." has left the chat session.</i><br></div>");
	fclose($fp);
	$sql= "UPDATE Users SET IsLogin='N' WHERE UserName='".$_SESSION['name']."'";
	$result1 = mysql_query($sql, $conn);
	if (!$result1) {
		echo "DB Error, could not query the database\n";
		echo 'MySQL Error: ' . mysql_error();
		exit;
}
session_destroy();//ending the session
header("Location: index.php"); //Redirect the user
}



function loginForm(){
echo'
<div id="loginform">
<form action="index.php" method="post">
<p>Please enter your name to continue:</p>
<label for="name">Name:</label>
<input type="text" name="name" id="name" />
<label for="name">Password:</label>
<input type="text" name="password" id="password" />
<input type="submit" name="enter" id="enter" value="Enter" />
</form>
</div>
';
}

if(isset($_POST['enter'])){
  if($_POST['name'] != ""){
     $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));//storing name is session
  }
  else{
  	$message = "Please type in a name.";//validation check for name
  	echo $message;
    $incorrectUser=true;
    
  }
  if($_POST['password'] != ""){
  	$_SESSION['password'] = stripslashes(htmlspecialchars($_POST['password']));//storing password is session
  }
  else{
  	$message = " \n Please type in a password.";//validation check for password
  	$incorrectPsswd=true;
  	echo $message;
  }
  if($_POST['name'] != "" && $_POST['password'] != "")
  {
  	//below we authenticate the user with our database and give error if username or password doesnot match
  	$sql= "SELECT count(UserName) as countUser,IsLogin FROM Users WHERE UserName='".$_SESSION['name']."'and Password='".$_SESSION['password']."'";
  	$result = mysql_query($sql, $conn);
  	
  	if (!$result) {
  		echo "DB Error, could not query the database\n";
  		echo 'MySQL Error: ' . mysql_error();
  		exit;
  	}
  	
  	while ($row = mysql_fetch_assoc($result)) {
  		if($row['countUser']==0){
  			$message = "Incorrect User ID or Password.";
  			echo "<script type='text/javascript'>alert('$message');</script>";
  			$incorrectAuthentication=true;
  			break;
  		}
  		elseif($row['IsLogin']=='Y')
  		{
  			$isLogged='Y';
  			$message = "User already logged in other browser.";
  			echo "<script type='text/javascript'>alert('$message');</script>";
  			break;
  		}
  		else {
  	
  			$sql= "UPDATE Users SET IsLogin='Y' WHERE UserName='".$_SESSION['name']."'";
  			$result1 = mysql_query($sql, $conn);
  			if (!$result1) {
  				echo "DB Error, could not query the database\n";
  				echo 'MySQL Error: ' . mysql_error();
  				exit;
  			}
  			//mysql_free_result($result1);
  		}
  	}
  	
  	mysql_free_result($result);
  }

}

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>chat</title>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>
<body>
<?php
if(!isset($_SESSION['name']) || !isset($_SESSION['password'])){
	loginForm();//redirecting to login page if session variables not set
}
elseif ($incorrectAuthentication==true){
	// resetting variables for incorrect user name and password
	unset($_SESSION['name']);
	unset($_SESSION['password']);
	$incorrectAuthentication=false;
	loginForm();
}
elseif($isLogged=='Y')
{
	//resetting variables if user is already logged in other browser to prevent session continuation
	unset($_SESSION['name']);
	unset($_SESSION['password']);
	$isLogged='N';
	loginForm();
}
else{
	/*if all validation check are satisfied we update the db with session flag set for the logged user
	 * and also display all the logged in users friend list*/
	$sql= "Select U.UserName,T.LogFile from (select F.FriendID,F.LogFile from Users U, Friends F where U.UserID=F.UserID and U.UserName='".$_SESSION['name']."') as T,Users U where T.FriendID=U.UserID";
	$result = mysql_query($sql, $conn);
	 
	if (!$result) {
		echo "DB Error, could not query the database\n";
		echo 'MySQL Error: ' . mysql_error();
		exit;
	}
	$FriendList='<div id="FriendsDiv">
		<p class="welcome1">Welcome, <b>'.$_SESSION['name'].'</b></p>
		<hr>
		<ul id="friendslist">';
	while ($row = mysql_fetch_assoc($result)) {
		$FriendList=$FriendList.'<li><a id="'.$row['LogFile'].'" href="#">'.$row['UserName'].'</a></li> <br>';
	}
	$FriendList=$FriendList."</ul></div>";	
?>
<?php 
echo $FriendList;//display friendlist
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js">
</script>
<script type="text/javascript">

$(document).ready(function(){
	   var selectedFriend="";
	   var logFile="";
	   $("#exit").live('click', function(){
		   //on exit we set the logout as true
	       var exit = confirm("Are you sure you want to end the session?");
	       if(exit==true){window.location = 'index.php?logout=true&logfile='+logFile;}
	   });

	$("a").click(function(){
		selectedFriend = $(this).text();//storing the clicked friends name
		logFile=this.id;//storing the log file for the respective friend and user 
		/*Dynamically create the chat window on click of a friend*/
		var wrapperDiv= $(document.createElement("div")).attr({ id: "wrapper" });
		var menuDiv= $(document.createElement("div")).attr({ id: "menu" });
		var para1=$('<p class="welcome">Chatting with, <b>'+selectedFriend+'</b></p>');
		var para2=$('<p class="logout"><a id="exit" href="#">Exit Chat</a></p>');
		var div1=$('<div style="clear:both"></div>');
		menuDiv.append(para1);
		menuDiv.append(para2);
		menuDiv.append(div1);
		wrapperDiv.append(menuDiv);
		var chatDiv = $(document.createElement("div")).attr({ id: "chatbox" });
		wrapperDiv.append(chatDiv);
		var mssg=$('<input name="usermsg" type="text" id="usermsg" size="63" />');
		wrapperDiv.append(mssg);
		var submit=$('<input name="submitmsg" type="button"  id="submitmsg" value="Send" />');
		wrapperDiv.append(submit);
		$("body").append(wrapperDiv);
		
		$("#submitmsg").click(function(){
			/*On click of send button we send the text by the user to his friend using ajax post method*/
		      var clientmsg = $("#usermsg").val();
		      $.post("post.php", {text: clientmsg,friend: selectedFriend});
		      $("#usermsg").attr("value", "");
		      return false;
		   });
		
		$.get( logFile+".html", function( data ) {
			/*displaying the log file contents*/
			$("#usermsg").before(data);
		});
		
		});
	
   setInterval (loadLog, 2500);//load the log contents after the specified time interval
                              
function loadLog(){
    var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
	
    $.ajax({ url: logFile+".html",
             cache: false,
             success: function(html){
                $("#chatbox").html(html);
                var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
                if(newscrollHeight > oldscrollHeight){
                    $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); 
                }
             },
    });
}
});
</script>
<?php 
}
?>

</body>
</html>
