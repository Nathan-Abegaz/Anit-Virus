<?php
//Heredoc for HTML code
    echo <<<_END
    <html><head><title>Anti-Virus</title></head><body>
    <h1>Anti-Virus </h1>
    <h2>User</h2>
    <form method = 'post' action = 'hw6.php' enctype = 'multipart/form-data'>
    Select File: <input type = 'file' name = 'filename' size = '10' >
    <input type = 'submit' name  = 'userUpload' value = 'Upload'><br><br>
        ------------------------------------------------- <br>
        <h2>Admin</h2>
        Username: <input type = 'text' name = 'advisor' size = '20'><br>
        Password: <input type = 'text' name = 'student' size = '20'><br>
        <input type = 'submit' name = 'signin' value = 'Sign In'<br><br><br>
        File name: <input type = 'text' name = 'search' size = '20'><br>
        Select File: <input type = 'file' name = 'adminFile' size = '10' >
        <input type = 'submit' name = 'adminUpload' value = 'Upload'><br><br>

    </form>
  _END;

  ?>