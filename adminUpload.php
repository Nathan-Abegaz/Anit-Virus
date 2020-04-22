<?php


//Heredoc for HTML code
echo <<<_END
<html><head><title>Anti-Virus</title></head><body>
<h1>Anti-Virus</h1>
<a href="user.php">Click here for user </a><br>
<h2>Admin-Upload</h2>
<form method = 'post' action = 'adminUpload.php' enctype = 'multipart/form-data'>
    File name: <input type = 'text' name = 'adminFileName' size = '20'><br>
    Select File: <input type = 'file' name = 'adminFile' size = '10' >
    <input type = 'submit' name = 'adminUploadButton' value = 'Upload'><br><br><br
</form>
_END;


//Check if admin has uploaded file
if($_FILES && isset($_POST['adminUploadButton'])){
    //Check if upladed file is a text files
    if($_FILES['adminFile']['type'] == 'text/plain'&& !empty($_POST['adminFileName'])){
        //Perform virus check here
        echo "File has been uploaded.";
    }
    else{
        echo '==================== <br>Input a File/Name or Incorrect file type <br>====================<br><br>';
    }
}


?>