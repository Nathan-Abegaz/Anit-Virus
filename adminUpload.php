<?php
//require_once 'sanitize.php';
require_once 'login.php';
//require_once 'setupAdmin.php';
//require_once 'sanitize.php';
//require_once 'adminUpload.php';

$conn = new mysqli($hn, $un, $pw, $db);
if($conn ->connect_error)die ("OOOPS");

function upload($conn){
    //Heredoc for HTML code
    echo <<<_END
    <h2>Admin-Upload</h2>
    <form method = 'post' action = 'adminUpload.php' enctype = 'multipart/form-data'>
        File name: <input type = 'text' name = 'adminFileName' size = '20'><br>
        Select File: <input type = 'file' name = 'adminFile' size = '10' >
        <input type = 'submit' name = 'adminUploadButton' value = 'Upload'><br><br><br
    </form>
    _END;


    //Check if admin has uploaded file
    if(file_exists($_FILES['adminFile']['tmp_name']) && !empty($_POST['adminFileName'])){
            //Perform virus check here
            $virus_signature = get_virus_signature($conn);
            $virus_name = mysql_entities_fix_string($conn, $_POST['adminFileName']);
            add_Virus_To_DB($conn, $virus_name, $virus_signature);
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
     fclose($fh); //Close file handler

}

//Adds Virus to database
function add_Virus_To_DB($conn, $virus_name, $virus_signature){
    $stmt = $conn->prepare("INSERT INTO virus_info VALUES(?,?)");
    $stmt->bind_param('ss',$virus_name, $virus_signature);;
    $stmt->execute();
    $stmt->close();
}
upload($conn);

function mysql_entities_fix_string($conn, $string){
    return htmlentities(mysql_fix_string($conn,$string));
}
function mysql_fix_string($conn, $string){
  if(get_magic_quotes_gpc()) $string = stripslashes($string);
  return $conn->real_escape_string($string);
}
?>