<?php
session_start();
require "autoload.php";
include_once('config.php');
use Abraham\TwitterOAuth\TwitterOAuth;
$connection = new TwitterOAuth(Consumer_Key, Consumer_Secret);
$request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => "https://twitter.cherry4mb.xyz/twitter_back.php"));
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
$url = $connection->url("oauth/authenticate", array("oauth_token" => $request_token['oauth_token']));
header('Location: ' . $url);
?>
</body>
</html>
