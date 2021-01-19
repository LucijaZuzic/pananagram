<?php 
  function OpenCon()
  {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $db = "pananagram";
    $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
    
    return $conn;
  }
      
  function CloseCon($conn)
  {
    $conn -> close();
  }

  $con = OpenCon();
  $data = json_decode(file_get_contents("php://input"));

  
  $username = $data->username;
  $newusername = $data->newusername;
  $userId = $data->userId;

  mysqli_query($con,"UPDATE users SET username='".$newusername."' WHERE userId='".$userId."'");
  mysqli_query($con,"UPDATE friends SET username1='".$newusername."' WHERE username1='".$username."'");
  mysqli_query($con,"UPDATE friends SET username2='".$newusername."' WHERE username2='".$username."'");    
  mysqli_query($con,"UPDATE block SET blocker ='".$newusername."' WHERE blocker='".$username."'");
  mysqli_query($con,"UPDATE block SET blocked ='".$newusername."' WHERE blocked='".$username."'");
  mysqli_query($con,"UPDATE friend_requests SET requester ='".$newusername."' WHERE requester='".$username."'");
  mysqli_query($con,"UPDATE friend_requests SET requested ='".$newusername."' WHERE requested='".$username."'");

  echo "Update successfully";
  CloseCon($conn);

?>