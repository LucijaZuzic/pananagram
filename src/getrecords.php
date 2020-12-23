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
    $userData = mysqli_query($con,"select * from highscore order by score asc");
    $response = array();
    while($row = mysqli_fetch_assoc($userData)){
      $response[] = $row;
    }
    echo json_encode($response);
    exit;
  }

// Add record
if($request == 2){
    $scoreid = $data->scoreid;
    $id = $data->id;
    $userId = $data->userId;
    $score = $data->score;
    $scoredate = date('Y-m-d H:i:s');

    $userData = mysqli_query($con,"SELECT * FROM highscore WHERE scoreid='".$scoreid."'");

    if(mysqli_num_rows($userData) == 0){
      mysqli_query($con,"INSERT INTO highscore(scoreid,id,userId,scoredate,score) VALUES('".$scoreid."','".$id."','".$userId."','".$scoredate."','".$score."')");
      echo (mysqli_affected_rows($con));
      echo (mysqli_error($con));
      echo "Insert successfully";
    }else{
      echo "ID already exists.";
    }
    exit;
  }
  
?>
 