<?php

require_once 'login.php';
require_once 'setupAdmin.php';

$conn = new mysqli($hn, $un, $pw, $db);
if($conn ->connect_error)die ("OOOPS");

//Heredoc for HTML code
echo <<<_END
<html><head><title>Anti-Virus</title></head><body>
<h1>Anti-Virus</h1>
<a href="midterm.php">Click here for user </a><br>
<h2>Admin</h2>
<form method = 'post' action = 'admin.php' enctype = 'multipart/form-data'>
    Username: <input type = 'text' name = 'username' size = '20'><br>
    Password: <input type = 'password' name = 'password' size = '20'><br>
    <input type = 'submit' name = 'signin' value = 'Sign In'<br><br><br>
</form>
_END;

///////////////Authentication/////////////////////
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['signin'])){
    
  $un_temp = mysql_entities_fix_string($conn, $_POST['username']);
  $pw_temp = mysql_entities_fix_string($conn, $_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM credentials WHERE username  = ? ");
  $stmt->bind_param('s', $un_temp);
  $stmt->execute();

  $result = $stmt->get_result();
  $stmt->close(); //close for security

  if(!$result)die("OOPS");
  elseif($result->num_rows){
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $result->close();//close for security

    $salt1 = row[$salt1];
    $salt2 = row[$salt2];
    $token = hash('ripemd128', "$salt1$pw_temp$salt2");

    if($token == $row['password']){
        header("Location: adminUpload.php");
        echo "You are not logged in as ". $row['username'];
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
        echo ("Please enter your username and password");
    }
    $conn->close(); // Close connection for security


//gets post from and sanitizes input
function mysql_entities_fix_string($conn, $string){
    return htmlentities(mysql_fix_string($conn,$string));
}
function mysql_fix_string($conn, $string){
  if(get_magic_quotes_gpc()) $string = stripslashes($string);
  return $conn->real_escape_string($string);
}

  ?>
