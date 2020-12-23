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

  $request = $data->request;

  // Fetch All records
  if ($request == 1) {
    $userData = mysqli_query($con,"select * from friends order by username1 asc");
    $response = array();
    while($row = mysqli_fetch_assoc($userData)){
      $response[] = $row;
    }
    echo json_encode($response);
    exit;
  }

// Fetch one record

if($request == 2){
  $username = $data->username;
  $userData = mysqli_query($con,"SELECT * FROM friends WHERE username1='".$username."' OR username2='".$username."'");
  $response = array();
  while($row = mysqli_fetch_assoc($userData)){
    $response[] = $row;
  }
  echo json_encode($response);
  exit;
}


// Add record
if($request == 3){
  $username1 = $data->username1;
  $username2 = $data->username2;
  $date = date('Y-m-d H:i:s');

  $userData = mysqli_query($con,"SELECT * FROM friends WHERE (username1='".$username1."' AND username2='".$username2."') OR (username1='".$username2."' AND username2='".$username1."')");

  if(mysqli_num_rows($userData) == 0){
    mysqli_query($con,"INSERT INTO friends(username1,username2,since) VALUES('".$username1."','".$username2."','".$date."')");
    echo (mysqli_affected_rows($con));
    echo (mysqli_error($con));
    echo "Insert successfully";
  }else{
    echo "Friendship already exists.";
  }
  exit;
}  

// Delete record
if($request == 4){
    $username1 = $data->username1;
    $username2 = $data->username2;  
  
    mysqli_query($con,"DELETE FROM friends WHERE (username1='".$username1."' AND username2='".$username2."') OR (username1='".$username2."' AND username2='".$username1."')");
  
    echo "Delete successfully";
    exit;
  }
?>
 