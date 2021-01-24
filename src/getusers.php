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
    $userData = mysqli_query($con,"select username,userId,status,visibility from users order by userId asc");
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
  $userData = mysqli_query($con,"SELECT username,userId,status,visibility FROM users WHERE username='".$username."'");
  $response = array();
  while($row = mysqli_fetch_assoc($userData)){
    $response[] = $row;
  }
  echo json_encode($response);
  exit;
}


// Add record
if($request == 3){
  $username = $data->username;
  $password = $data->password;
  $password = password_hash ( $password , PASSWORD_DEFAULT);
  $userId = $data->userId;
  $status = $data->status;

  $userData = mysqli_query($con,"SELECT * FROM users WHERE username='".$username."'");

  if(mysqli_num_rows($userData) == 0){
    mysqli_query($con,"INSERT INTO users(username,password,userId,status,visibility) VALUES('".$username."','".$password."','".$userId."','".$status."',0)");
    echo (mysqli_affected_rows($con));
    echo (mysqli_error($con));
    echo "Insert successfully";
  }else{
    echo "ID already exists.";
  }
  exit;
}  

// Update all but password
if($request == 4){
  $username = $data->username;
  $userId = $data->userId;
  $status = $data->status;
  $visibility = $data->visibility;
  mysqli_query($con,"UPDATE users SET userId='".$userId."',status='".$status."',visibility='".$visibility."' WHERE username='".$username."'");
  echo (mysqli_affected_rows($con));
  echo (mysqli_error($con));
  echo "Update successfully";
  exit;
}  

// Update password
if($request == 5){
  $password = $data->password;
  $password = password_hash ( $password , PASSWORD_DEFAULT);
  $userId = $data->userId;
  mysqli_query($con,"UPDATE users SET password='".$password."' WHERE userId='".$userId."'");
  echo (mysqli_affected_rows($con));
  echo (mysqli_error($con));
  echo "Update successfully";
  exit;
}

?>
 