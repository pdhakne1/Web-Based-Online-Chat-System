<?php
include 'index.php';
session_start();
if(isset($_SESSION['name'])){
	/* Here depending on the friend selected of chat by the user we dynamically create/write to a log file all the chats between them */	
   $text = $_POST['text'];
   $conn=get_connection();
   $sql= " Select F.LogFile from Friends F where F.FriendID in (Select UserID from Users where UserName='".$_POST['friend']."') and F.UserID in (Select UserID from Users where UserName='".$_SESSION['name']."')";
   $result = mysql_query($sql, $conn);
    
   if (!$result) {
   	echo "DB Error, could not query the database\n";
   	echo 'MySQL Error: ' . mysql_error();
   	exit;
   }
   while ($row = mysql_fetch_assoc($result)) {
   	echo $row['LogFile'];
   	$LogFileName=$row['LogFile'];
   }

   $fp = fopen($LogFileName.'.html', 'a');
   fwrite($fp, "<div class='msgln'>(".date("g:i A").") <b>".$_SESSION['name']."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
   fclose($fp);
}
?>
