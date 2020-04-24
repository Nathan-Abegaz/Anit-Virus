<?php
require_once 'login.php';
require_once 'sanitize.php';

    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn ->connect_error)die ("OOOPS");


//Heredoc for HTML code
    echo <<<_END
    <html><head><title>Anti-Virus</title></head><body>
    <h1>Anti-Virus </h1>
    <a href="adminAuthenticate.php">Click here for admin </a><br><br>
    <h2>User</h2>
    <form method = 'post' action = 'user.php' enctype = 'multipart/form-data'>
    Select File: <input type = 'file' name = 'userFile' size = '10' >
    <input type = 'submit' name  = 'userUploadButton' value = 'Upload'><br><br>    
    </form>
  _END;

  //Checks if user has uploaded file
  if(file_exists($_FILES['userFile']['tmp_name'])){
    //Perform virus scan
    virus_scan($conn);
  }
  else{
    echo '==================== <br>Input a File <br>====================<br><br>';
  }

  function virus_scan($conn){
    $file_size = $_FILES['userFile']['size'];
    if($file_size < 20) die("File is not a virus");

    $file = $_FILES['userFile']['tmp_name'];
    $fh = fopen($file, 'r') or  die ("File does not exists or you lack permisison to open it");
    for($i = 0; $i < $file_size; $i++){
      $data =  mysql_entities_fix_string($conn,fread($fh, 20));
      //echo $data;
      search_virus($conn, $data);
      fseek($fh,20);
    }
    fclose($fh);
  }

  function search_virus($conn, $data){
    $stmt = $conn->prepare("SELECT * FROM virus_info WHERE signature  = ? ");
    $stmt->bind_param('s', $data);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close(); 
    if(!$result) die ("OOOPS");

    //Get number of rows
    $rows = $result->num_rows;

    if($rows != 0){
      for($j = 0; $j < $rows; ++$j){
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        "Your file contains a malware called " . $row['name']. "<br>";
      }

      $result->close();
      $conn->close();
    }
    else{
      echo "The file is not infected";
    }

  }
  ?>


  