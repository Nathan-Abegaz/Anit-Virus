<?php

require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db);
if($conn ->connect_error)die ("OOOPS");
$admim = 'admin';
$stmt = $conn->prepare("SELECT * FROM credentials WHERE username  = ? ");
  $stmt->bind_param('s', $admin);
  $stmt->execute();


  $result = $stmt->get_result();
  $stmt->close(); //close for security
  if(!$result) die ("OOPS");
  //Get number of $rows
  $rows = $result->num_rows;
  //Iterate through each row retrieving and printing the data
  if($rows != 0){
      echo $rows;
    for($j = 0; $j < $rows; ++$j){
      $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_ASSOC);

      echo 'Username ' . $row['username']. '<br>';
      echo 'Password ' . $row['password']. '<br>';
      echo 'Salt1' . $row['salt1']. '<br> ';
      echo 'Salt2 ' . $row['salt2']. '<br><br>';
    }
}

    //Close connection for security
    $result->close();
    $conn->close();
?>