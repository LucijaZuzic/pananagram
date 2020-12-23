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
    $userData = mysqli_query($con,"select * from puzzles order by id asc");
    $response = array();
    while($row = mysqli_fetch_assoc($userData)){
      $response[] = $row;
    }
    echo json_encode($response);
    exit;
  }

// Add record
if($request == 2){
  $id = $data->id;
  $num_order = $data->num_order;
  $solution = $data->solution;
  $word = $data->word;
  $descriptions = mysqli_real_escape_string($con, $data->descriptions);
  $borders = $data->borders;
  $barriers = $data->barriers;
  $intro = mysqli_real_escape_string($con, $data->intro);
  $numwords = $data->numwords;
  $max_word_len = $data->max_word_len;
  $row_with_hint = $data->row_with_hint;
  $authorid = $data->authorid;
  $date = date('Y-m-d H:i:s');
  $userData = mysqli_query($con,"SELECT * FROM puzzles WHERE id=".$id."");
  if(mysqli_num_rows($userData) == 0){
    mysqli_query($con,"INSERT INTO puzzles(id,num_order,solution,word,descriptions,borders,barriers,intro,numwords,max_word_len,row_with_hint,authorid,created,updated) VALUES('".$id."','".$num_order."','".$solution."','".$word."','".$descriptions."','".$borders."','".$barriers."','".$intro."','".$numwords."','".$max_word_len."','".$row_with_hint."','".$authorid."','".$date."','".$date."')");
    echo (mysqli_affected_rows($con));
    echo (mysqli_error($con));
    echo "Insert successfully";
  }else{
    echo "ID already exists.";
  }

  exit;
}
 
// Update record
if($request == 3){
  $id = $data->id;
  $num_order = $data->num_order;
  $solution = $data->solution;
  $word = $data->word;
  $descriptions = mysqli_real_escape_string($con, $data->descriptions);
  $borders = $data->borders;
  $barriers = $data->barriers;
  $intro = mysqli_real_escape_string($con, $data->intro);
  $numwords = $data->numwords;
  $max_word_len = $data->max_word_len;
  $row_with_hint = $data->row_with_hint;
  $authorid = $data->authorid;
  $date = date('Y-m-d H:i:s');
  mysqli_query($con,"UPDATE puzzles SET num_order='".$num_order."',solution='".$solution."',word='".$word."',descriptions='".$descriptions."',borders='".$borders."',barriers='".$barriers."',intro='".$intro."',numwords='".$numwords."',max_word_len='".$max_word_len."',row_with_hint='".$row_with_hint."',authorid='".$authorid."',updated='".$date."' WHERE id=".$id."");
  echo (mysqli_affected_rows($con));
  echo (mysqli_error($con));
  echo "Update successfully";
  exit;
}

// Delete record
if($request == 4){
  $id = $data->id;

  mysqli_query($con,"DELETE FROM puzzles WHERE id=".$id."");

  echo "Delete successfully";
  exit;
}

// Fetch one record

if($request == 5){
  $id = $data->id;
  $userData = mysqli_query($con,"SELECT * FROM puzzles WHERE id=".$id."");
  $response = array();
  while($row = mysqli_fetch_assoc($userData)){
    $response[] = $row;
  }
  echo json_encode($response);
  exit;
}


?>
 