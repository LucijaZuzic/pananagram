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
    $userData = mysqli_query($con,"select * from friend_requests order by requester asc");
    $response = array();
    while($row = mysqli_fetch_assoc($userData)){
      $response[] = $row;
    }
    echo json_encode($response);
    exit;
  }

// Fetch requested

if($request == 2){
  $requested = $data->requested;
  $userData = mysqli_query($con,"SELECT * FROM friend_requests WHERE requested='".$requested."'");
  $response = array();
  while($row = mysqli_fetch_assoc($userData)){
    $response[] = $row;
  }
  echo json_encode($response);
  exit;
}

// Fetch requester

if($request == 3){
    $requester = $data->requester;
    $userData = mysqli_query($con,"SELECT * FROM friend_requests WHERE requester='".$requester."'");
    $response = array();
    while($row = mysqli_fetch_assoc($userData)){
      $response[] = $row;
    }
    echo json_encode($response);
    exit;
}
  
// Add record
if($request == 4){
  $requester = $data->requester;
  $requested = $data->requested;
  $date = date('Y-m-d H:i:s');

  $userData = mysqli_query($con,"SELECT * FROM friend_requests WHERE (requester='".$requester."' AND requested='".$requested."')");

  if(mysqli_num_rows($userData) == 0){
    mysqli_query($con,"INSERT INTO friend_requests(requester,requested,sent) VALUES('".$requester."','".$requested."','".$date."')");
    echo (mysqli_affected_rows($con));
    echo (mysqli_error($con));
    echo "Insert successfully";
  }else{
    echo "Request already exists.";
  }
  exit;
}  

// Delete record
if($request == 5){
    $requester = $data->requester;
    $requested = $data->requested;
  
    mysqli_query($con,"DELETE FROM friend_requests WHERE (requester='".$requester."' AND requested='".$requested."')");
  
    echo "Delete successfully";
    exit;
  }
  
?>
 