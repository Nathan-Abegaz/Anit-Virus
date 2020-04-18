<?php
    require_once 'login.php';

    $conn = new mysqli($hn, $un, $pw, $db); //Establish a connection
    if($conn->connect_error) die("OOPS");

    //Heredoc for HTML code
    echo <<<_END
    <html><head><title>Assignment 6</title></head><body>
    <h1>Assignmet 6 </h1>
    <h2>Add Info</h2>
    <form method = 'post' action = 'hw6.php' enctype = 'multipart/form-data'>
        Advisor Name: <input type = 'text' name = 'advisor' size = '20'><br>
        Student Name: <input type = 'text' name = 'student' size = '20'><br>
        Student ID: <input type = 'text' name = 'id' size = '20'><br>
        Class Code: <input type = 'text' name = 'code' size = '20'>  <br>
        <input type = 'submit' name = 'add'  value = 'Add'> <br><br>
        ------------------------------------------------- <br>
        <h2>Search for advisor</h2>
        Advisor name: <input type = 'text' name = 'search' size = '20'><br>
        <input type = 'submit' name = 'searchButton' value = 'Search'<br>

    </form>
  _END;


//Checks if add button is pressed
if(isset($_POST['add'])){
  //Checks to see if all values are filled
  if(!empty($_POST['advisor']) && !empty($_POST['student'])&& !empty($_POST['id'])&& !empty($_POST['code'])){
      updateDB($conn);
    }
  else{
      echo "Please fill in every values";
  }
}

//Check if search button is pressed
if(isset($_POST['searchButton'])){
  //Check to see if search value is inputted
  if(!empty($_POST['search'])){
      search($conn);
  }
  else{
    echo 'Enter Advisor name';
  }
}


//Retrieves user input and uploads it into the database
function updateDB($conn){
    //Sanitize user input
    $advisorName =  mysql_entities_fix_string($conn, $_POST['advisor']);
    $studentName =  mysql_entities_fix_string($conn, $_POST['student']);
    $studentID =  mysql_entities_fix_string($conn, $_POST['id']);
    $classCode = mysql_entities_fix_string($conn, $_POST['code']);

    $stmt = $conn->prepare('INSERT INTO inputTable VALUES(?,?,?,?)');
    $stmt->bind_param('ssis',$advisorName, $studentName, $studentID, $classCode);

    $stmt->execute();
    $stmt->close();
}

//Prints output the content of specified advisor
function search($conn){

  //Sanitize input
  $searchedAdvisor = mysql_entities_fix_string($conn, $_POST['search']);

  //Make query to database to retireve specified advisor with name
  $stmt = $conn->prepare("SELECT * FROM inputTable WHERE advisor_name  = ? ");
  $stmt->bind_param('s', $searchedAdvisor);
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

      echo 'Advisor Name: ' . $row['advisor_name']. '<br>';
      echo 'Student Name: ' . $row['student_name']. '<br>';
      echo 'Student ID: ' . $row['student_id']. '<br> ';
      echo 'Class Code: ' . $row['class_code']. '<br><br>';
    }

    //Close connection for security
    $result->close();
    $conn->close();
  }
  else{
    echo "Advisor doesn't exist";
  }

}

//gets post from and sanitizes input
function mysql_entities_fix_string($conn, $string){
    return htmlentities(mysql_fix_string($conn,$string));
}
function mysql_fix_string($conn, $string){
  if(get_magic_quotes_gpc()) $string = stripslashes($string);
  return $conn->real_escape_string($string);
}



?>
