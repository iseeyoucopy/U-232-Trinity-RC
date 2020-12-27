<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2020 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
	
	$me = $_SESSION['JD_CURRUNT_USER'];
	
	if(isset($_POST['send']))
	{		
		$to     = $_POST['to'];
		$msg    = sqlesc( $_POST['msg'] );
		$date   = date("Y-m-d H:i:s");
		
		$query = sql_query( "INSERT INTO chat(`id`, `sender`, `reciever`, `msg`, `time`)  VALUES(0,'$me','$to','$msg','$date')" );		
	}
	
	if(isset($_POST['get_all_msg']) && isset($_POST['user']))
	{
		$return_string="";
		$set_unread="";
		$user = sqlesc($_POST['user']);
		$data=sql_query("SELECT * FROM chat WHERE (sender = '$me' AND reciever = '$user') OR (sender = '$user' AND reciever = '$me') ORDER BY (time) DESC");
		while($row = mysqli_fetch_object($data))
		{
			$class="other";
			if($row->sender == $me) $class = "me";
			$set_unread.="'".$row->id."',";
			$return_string.="<span class='$class' > $row->msg </span>";
		}
		$set_unread = trim($set_unread , ",");
		
		sql_query("UPDATE chat SET status=1 WHERE id IN ".$set_unread ."");
		
		echo $return_string;
	}
	
	if(isset($_POST['unread']))
	{
		$return_string=array();
		$set_unread="";
		$data=sql_query("SELECT * FROM chat WHERE  reciever = '$me' AND status=0 ORDER BY (time) DESC");
		while($row = mysqli_fetch_object($data))
		{					
			$return_string[$row->sender][]=$row->msg;
			$set_unread.="'".$row->id."',";
		}
		$set_unread = trim($set_unread , ",");
		
		sql_query("UPDATE chat SET status=1 WHERE id IN($set_unread)");
		
		print json_encode($return_string);
	}
	
?>