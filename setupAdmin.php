<?php

require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db);
if($conn ->connect_error)die ("OOOPS");

//Create randomly generate salt function
$salt1 = random_bytes(5); 
$salt2 = random_bytes(5);

//Create username and password
$username = 'a';
$password = 'sjsu';

$token = hash('ripemd128', "$salt1$password$salt2");
add_admin($conn, $username, $token, $salt1, $salt2);

function add_admin($conn, $username, $token, $salt1, $salt2){

    $stmt = $conn->prepare("INSERT INTO credentials VALUES(?,?,?,?)");
    $stmt->bind_param('ssss',$username, $token, $salt1, $salt2);
    

    $stmt->execute();
    $stmt->close();
}
?>

