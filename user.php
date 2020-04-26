<?php
require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db);
if($conn ->connect_error)die ("OOOPS");


//Heredoc for User-Upload html code
    echo <<<_END
    <html><head><title>Anti-Virus</title></head><body>
    <h1>Anti-Virus </h1>
    <h4>User-Uploads File</h4>
    <form method = 'post' action = 'user.php' enctype = 'multipart/form-data'>
    Select File: <input type = 'file' name = 'userFile' size = '10' >
    <input type = 'submit' name  = 'userUploadButton' value = 'Upload'><br><br>    
    </form>
  _END;

  //Checks if user has uploaded file
  if($_FILES){
    if(file_exists($_FILES['userFile']['tmp_name'])){
      //Perform virus scan
      virus_scan($conn);
    }
    else{
      echo '==================== <br>Input a File <br>====================<br><br>';
    }
  }
  

  $conn->close();  //Close connection
  
  //Helper funciton that scans through entire uploaded file for virus signature stored in admin database
  function virus_scan($conn){
    
    $notInfected = TRUE;

    //Retrieve File
    $file_size = $_FILES['userFile']['size'];
    if($file_size < 20) die("File is not a virus"); 
    $file = $_FILES['userFile']['tmp_name'];
    $fh = fopen($file, 'r') or  die ("File does not exists or you lack permisison to open it");

    //Iterate thorugh the file, checking each 20 bytes for virus signature 
    for($i = 0; $i <= $file_size-20; $i++){
      fseek($fh, $i);
      $data =  mysql_entities_fix_string($conn, fread($fh,20)); // sanitize

      //Search for virus signature in admin database
      if(search_virus($conn, $data)) {
        $notInfected = FALSE;
        break;
      }
    }
    fclose($fh); // Close file pointer
    if($notInfected){
      echo "The file is not infected";
    }

  }

//Helper function that searches for virus in admin database
function search_virus($conn,$data){
  
  $stmt = $conn->prepare("SELECT * FROM virus_info WHERE virus_signature  = ? ");
  $stmt->bind_param('s', $data);
  $stmt->execute();
  
  $result = $stmt->get_result();
  $stmt->close(); //close for security
  if(!$result) die ("OOPS");
  //Get number of $rows
  $rows = $result->num_rows;
  //Iterate through each row retrieving and printing the data
  if($rows != 0){
    for($j = 0; $j < $rows; ++$j){
      $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_ASSOC);
      echo 'Your file contains a malware called ' . $row['virus_name']. '<br>';
      return TRUE;
    }
    //Close connection for security
    $result->close();
  }
  else{
    return FALSE;
  }
}

//Sanitizes input
function mysql_entities_fix_string($conn, $string){
  return htmlentities(mysql_fix_string($conn,$string));
}
function mysql_fix_string($conn, $string){
if(get_magic_quotes_gpc()) $string = stripslashes($string);
return $conn->real_escape_string($string);
}

  ?>


  