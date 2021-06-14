<?php session_start(); ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>Twitter</title>
  </head>
  <style type="text/css">
    html, body {
      height: 100%;
      width: 100%;
    }

    html {
        display: table;
        margin: auto;
      }

    body {
      height: auto;
      width: 100%;
      background-color: #4f4f4f;
      display: table-cell;
    }
    
    #main{
      margin: auto;;
      width: 100%;
      height: 100%;
      background-color: #fff;
    }
    #userbg{
       /* The image used */
    background-color: #cccccc; /* Used if the image is unavailable */
    background-position: center; /* Center the image */
    background-repeat: no-repeat; /* Do not repeat the image */
    background-size: cover; /* Resize the background image to cover the entire container */
    height: 100px;
    }
  </style>
  <body>
<?php
include("config.php");
require "autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

if(isset($_POST['logout']))
{
  session_destroy();echo"Logged Out";echo "<script language='javascript'>window.location.replace('logout.html');</script>";exit();
} 


if(isset($_SESSION['t1']))
  $connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $_SESSION['t1'], $_SESSION['t2']);

if(isset($_POST['tweeting'])){
  $tweetText = $_POST['tweetText'];
                      // TEXT Tweet
  if ($_FILES['tweetimg']['error'] == 4){
    $statues = $connection->post("statuses/update", ["status" => $tweetText]);
    if ($connection->getLastHttpCode() == 200) {
      echo '<div class="alert alert-success" role="alert">Success</div>';
    } else {
      echo '<div class="alert alert-warning" role="alert">Something went wrong</div>';
    }
  }
                      // Media Tweet
  else{
    $location=$_FILES['tweetimg']['tmp_name'];
    // echo $location;exit();
    $connection->setTimeouts(100, 15);
    $media = $connection->upload('media/upload', ['media' => $location]);
    $parameters = [
    'status' => $tweetText,
    'media_ids' => $media->media_id_string
    ];
    $result = $connection->post('statuses/update', $parameters);
    if ($connection->getLastHttpCode() == 200) {
      echo '<div class="alert alert-success" role="alert">Success</div>';
    } else {
      echo '<div class="alert alert-warning" role="alert">Something went wrong</div>';
    }
  }
  echo'<script>history.pushState({}, "", "")</script>';
}

?>
    <div id="main">
<?php
if(!isset($_SESSION['t1'])){
//   header("location:twitter_login.php");
?>
<script>window.location.replace("twitter_login.php");</script>
  <?php
}
else{
  $user_info = $connection->get('account/verify_credentials');

  echo "<div id='userbg' style='padding:20px;vertical-align:middle;background-image: url(".$user_info->profile_banner_url.");'><span style='float:left;padding-top:0px;background-color:rgba(0, 0, 0,0.9);color:white;text-shadow: 0 0 3px #fff, 0 0 5px #fff;'>".$user_info->name."<br>";

  echo "@".$user_info->screen_name;

  $_SESSION['screen_name']=$user_info->screen_name;

  $dp = $user_info->profile_image_url_https;
  $dp = str_replace("_normal", "", $dp);
  echo "</span><img src='".$dp."' width='100px' style='float:right;'/>";
  echo "</div><br><br><br><hr style='height: 3px; background-color: black;'/>"
?>
  <form action="index.php" id="form" method="post" enctype="multipart/form-data">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="float:right;margin-right: 20px;">
     Tweet
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tweet</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- body here -->
            <div class="input-group">
              <textarea class="form-control" name='tweetText' aria-label="With textarea" placeholder="Enter Text"></textarea>
            </div>
              <input type="file" name="tweetimg">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" name="tweeting" value="Tweet" />
          </div>
        </div>
      </div>
    </div>
<!-- Modal end -->
  </form>

<?php

// print_r($user_info);
$time = str_replace("+0000", "", $user_info->created_at);
$time = strtotime($time);
$time = date("d-m-Y(D) H:i:s",$time);  

$time = "Account created on(GMT):".$time;
$foll = "Followers:".$user_info->followers_count;
$frnd = "Friends:".$user_info->friends_count;
$stat = "Tweet/Retweets:".$user_info->statuses_count;
$favc = "Your Favorites:".$user_info->favourites_count;
$Pdata = $time."\n".$foll."\n".$frnd."\n".$stat."\n".$favc."\nfrom:#urstrulyCherryy\nvisit: twitter.cherry4MB.xyz";

echo "<form action='' method='post'><div style='border:1px solid black;width:90%;float:left;margin-left:5%;padding:1%'>";
echo $time."<br>".$foll."<br>".$frnd."<br>".$stat."<br>".$favc;
echo'<br><input type="submit" name="tweetData" class="btn btn-primary" style="float:left;margin:5px" value="Tweet Above Data"/>';
echo'<button type="button" onclick="copyFunc()" class="btn btn-primary" style="float:left;margin:5px">
 Copy Above Data
</button>';
?>
<textarea id="copyInp" name='tweetvalue' style="position:absolute;left:-1000px;top:-1000px;"><?php echo $Pdata; ?></textarea> 
<?php

echo "</div></form><br>";

if(isset($_POST['tweetData'])){
  if(isset($_SESSION['t1'])){
    $tweetText=$_POST['tweetvalue'];
    $connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $_SESSION['t1'], $_SESSION['t2']);
    $statues = $connection->post("statuses/update", ["status" => $tweetText]);
    if ($connection->getLastHttpCode() == 200) {
      ?>
      
      <script>alert("Tweeted");history.pushState({}, "", "")</script>
      <?php
    } else {

    }
  }
}

echo"<script language='javascript'>
function copyFunc() {
  var copyText = document.getElementById('copyInp');
  copyText.select();
  document.execCommand('copy');
  alert('Copied');
}
</script>";

}

?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </div>
  <form method="post" action="">
  <input type="submit" class="btn btn-primary" name="logout" value="LOGOUT" style="position: fixed;bottom: 0;right: 0;" />  
</form>
  </body>
</html>
