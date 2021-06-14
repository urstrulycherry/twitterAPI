<?php
session_start();
include("config.php");
require "autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;
if(isset($_SESSION['t1']))
{
	// echo "ok";
	echo "<script language='javascript'>window.location.replace('index.php');</script>";
	echo "<script language='javascript'>alert('setted');</script>";
	
}
else{
	$connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	 
	$access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $_REQUEST['oauth_verifier'], 'oauth_token'=> $_GET['oauth_token']));
	
	$_SESSION['t1']=$access_token['oauth_token'];
	$_SESSION['t2']=$access_token['oauth_token_secret'];
	
}
echo "<script language='javascript'>window.location.replace('index.php');</script>";
// header('Location:index.php');
	
// print_r($user_info);
// echo $user_info->id."<br>";
// echo $user_info->name;

/*$statues = $connection->post("statuses/update", ["status" => "Evaraina unara online lo???"]);
if($connection->getLastHttpCode() == 200){
	echo"Succeeded";
}
else{
	echo "Nope";
}
*/

?>