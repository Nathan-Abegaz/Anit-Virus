<?php

require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db);
if($conn ->connect_error)die ("OOOPS");

///////////////Authentication/////////////////////
  if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
    
    //Fetch typed in username and password
    //Sanitize input
    $un_temp = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_USER']);
    $pw_temp = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_PW']);
    
    //Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT * FROM credentials WHERE username  = ? ");
    $stmt->bind_param('s', $un_temp);
    $stmt->execute();

    //Retrieve results from query
    $result = $stmt->get_result();
    $stmt->close(); //close for security

    //Error handling
    if(!$result)die("OOPS");

    
    elseif($result->num_rows){
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $result->close();//close for security

      //Retrieve token from database
      $token = $row['password'];

      //Checks if input password is the same with password in database
      //Used in-built php function password_verify to verify credentials
      if(password_verify($pw_temp,$token)){
          echo "You are logged in as ". $row['username'];
      }
      else{
          die("Invalid username/password combniation");
      }
    }
    else{
          die("Invalid username/password combniation");
      }
  }
else{
  header('WWW-Authenticate: Basic realm="Restricted Section"');
  header('HTTP/1.0 401 Unauthorized');
  die ("Please enter your username and password");
}

//Here doc for admin uploading infected files
echo <<<_END
<html><head><title>Anti-Virus</title></head><body>
<h1>Anti-Virus-Scanner</h1>
<h4>Admin-Infected File Upload</h4>
    <form method = 'post' action = 'admin.php' enctype = 'multipart/form-data'>
        File name: <input type = 'text' name = 'adminFileName' size = '20'><br>
        Select File: <input type = 'file' name = 'adminFile' size = '10' >
        <input type = 'submit' name = 'adminUploadButton' value = 'Upload'><br><br><br
    </form>
_END;

//Check if admin has uploaded file
if($_FILES){
  if(file_exists($_FILES['adminFile']['tmp_name']) && !empty($_POST['adminFileName'])){
    //Perform virus check here
      $virus_signature = get_virus_signature($conn);
      $virus_name = mysql_entities_fix_string($conn, $_POST['adminFileName']);
      add_Virus_To_DB($conn, $virus_name, $virus_signature);
      echo "$virus_name and its signature have been succesfuly been uploaded to database";
    }
    else{
      echo '==================== <br>Input a File or Name <br>====================<br><br>';
    }
}


//Gets virus signature;
function get_virus_signature($conn){
  $file = $_FILES['adminFile']['tmp_name'];
  $fh = fopen($file, 'r') or  die ("File does not exists or you lack permisison to open it");
  $file_size = $_FILES['adminFile']['size'];

  if($file_size >= 20){
  //Retrieve sanitized signature
    $signature =  mysql_entities_fix_string($conn,fread($fh, 20));
    return $signature;
  }
  else{
    die('File is not large enough');
  }
  fclose($fh); //Close file handler
}
//Adds Virus to database
function add_Virus_To_DB($conn, $virus_name, $virus_signature){
  $stmt = $conn->prepare("INSERT INTO virus_info VALUES(?,?)");
  $stmt->bind_param('ss',$virus_name, $virus_signature);;
  $stmt->execute();
  $stmt->close();
}
$conn->close(); // Close connection for security
 

//Sanitizes input
function mysql_entities_fix_string($conn, $string){
  return htmlentities(mysql_fix_string($conn,$string));
}
function mysql_fix_string($conn, $string){
if(get_magic_quotes_gpc()) $string = stripslashes($string);
return $conn->real_escape_string($string);
}
  ?>
