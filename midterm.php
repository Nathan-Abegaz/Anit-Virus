<?php
//Heredoc for HTML code
    echo <<<_END
    <html><head><title>Anti-Virus</title></head><body>
    <h1>Anti-Virus </h1>
    <h2>User</h2>
    <form method = 'post' action = 'midterm.php' enctype = 'multipart/form-data'>
    Select File: <input type = 'file' name = 'userFile' size = '10' >
    <input type = 'submit' name  = 'userUploadButton' value = 'Upload'><br><br>
        ------------------------------------------------- <br>
        <h2>Admin</h2>
        Username: <input type = 'text' name = 'advisor' size = '20'><br>
        Password: <input type = 'text' name = 'student' size = '20'><br>
        <input type = 'submit' name = 'signin' value = 'Sign In'<br><br><br>
        File name: <input type = 'text' name = 'adminFileName' size = '20'><br>
        Select File: <input type = 'file' name = 'adminFile' size = '10' >
        <input type = 'submit' name = 'adminUploadButton' value = 'Upload'><br><br>
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