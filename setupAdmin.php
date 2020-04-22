<?php

require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db);
if($conn ->connect_error)die ("OOOPS");

//Create username and password for admin
$username = 'admin';
$password = 'sjsu';

//Utilize builtin php function password_hash
//Creates a token that consists of password hash with a generated salt 
$token = password_hash($password, PASSWORD_DEFAULT);

add_admin($conn, $username, $token);

//Helper function that adds admin to database
//Creates a query that stores admin's username and generated token to database
function add_admin($conn, $username, $token){
    $stmt = $conn->prepare("INSERT INTO credentials VALUES(?,?)");
    $stmt->bind_param('ss',$username, $token);
    $stmt->execute();
    $stmt->close();
}
?>

