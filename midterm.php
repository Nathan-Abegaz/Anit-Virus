<?php
//Heredoc for HTML code
    echo <<<_END
    <html><head><title>Anti-Virus</title></head><body>
    <h1>Anti-Virus </h1>
    <a href="admin.php">Click here for admin </a><br><br>
    <h2>User</h2>
    <form method = 'post' action = 'midterm.php' enctype = 'multipart/form-data'>
    Select File: <input type = 'file' name = 'userFile' size = '10' >
    <input type = 'submit' name  = 'userUploadButton' value = 'Upload'><br><br>    
    </form>
  _END;

  //Checks if user has uploaded file
  if($_FILES && isset($_POST['userUploadButton'])){
      //Check if upladed file is a text files
      if($_FILES['userFile']['type'] == 'text/plain'){
        //Perform virus check here
        echo "File has been uploaded";
      }
      else{
        echo '==================== <br>Input a File or Incorrect file type <br>====================<br><br>';
      }
  }

  ?>