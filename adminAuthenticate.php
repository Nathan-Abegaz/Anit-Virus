<?php

require_once 'login.php';
//require_once 'setupAdmin.php';
require_once 'sanitize.php';
//require_once 'adminUpload.php';

$conn = new mysqli($hn, $un, $pw, $db);
if($conn ->connect_error)die ("OOOPS");

$user_signed_in = false;

//Heredoc for HTML code
echo <<<_END
<html><head><title>Anti-Virus</title></head><body>
<h1>Anti-Virus</h1>
<a href="user.php">Click here for user </a><br>
<h2>Admin</h2>
<form method = 'post' action = 'adminAuthenticate.php' enctype = 'multipart/form-data'>
    Username: <input type = 'text' name = 'username' size = '20'><br>
    Password: <input type = 'password' name = 'password' size = '20'><br>
    <input type = 'submit' name = 'signin' value = 'Sign In'<br><br><br>
    ------------------------------------------------------<br><br>
</form>
_END;

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
        $user_signed_in = true;
        echo $user_signed_in;
        //upload();
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
    header('WWW-Authenticate: BAsice realm="Restricted SEction"');
    header('HTTP/1.0 401 Unauthorized');
    die ("Please enter your username and password");
  }
    $conn->close(); // Close connection for security
  ?>
