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
    $password = $data->password;

    $hash = "";
    $userData = mysqli_query($con, "SELECT password FROM users WHERE username='".$username."'");
    $res = array();
    while($row = mysqli_fetch_assoc($userData)){
      $res[] = $row;
      $hash = $row["password"];
    }
    $response = password_verify($password, $hash);
    echo json_encode($response);
?>
 