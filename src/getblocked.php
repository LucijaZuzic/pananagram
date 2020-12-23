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
    $userData = mysqli_query($con,"select * from block order by blocker asc");
    $response = array();
    while($row = mysqli_fetch_assoc($userData)){
      $response[] = $row;
    }
    echo json_encode($response);
    exit;
  }

// Fetch blocker

if($request == 2){
  $blocker = $data->blocker;
  $userData = mysqli_query($con,"SELECT * FROM block WHERE blocker='".$blocker."'");
  $response = array();
  while($row = mysqli_fetch_assoc($userData)){
    $response[] = $row;
  }
  echo json_encode($response);
  exit;
}

// Fetch blocked

if($request == 3){
    $blocked = $data->blocked;
    $userData = mysqli_query($con,"SELECT * FROM block WHERE blocked='".$blocked."'");
    $response = array();
    while($row = mysqli_fetch_assoc($userData)){
      $response[] = $row;
    }
    echo json_encode($response);
    exit;
}
  
// Add record
if($request == 4){
  $blocker = $data->blocker;
  $blocked = $data->blocked;
  $date = date('Y-m-d H:i:s');

  $userData = mysqli_query($con,"SELECT * FROM block WHERE (blocker='".$blocker."' AND blocked='".$blocked."')");

  if(mysqli_num_rows($userData) == 0){
    mysqli_query($con,"INSERT INTO block(blocker,blocked,sent) VALUES('".$blocker."','".$blocked."','".$date."')");
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
    $blocker = $data->blocker;
    $blocked = $data->blocked;
  
    mysqli_query($con,"DELETE FROM block WHERE (blocker='".$blocker."' AND blocked='".$blocked."')");
  
    echo "Delete successfully";
    exit;
  }
  
?>
 