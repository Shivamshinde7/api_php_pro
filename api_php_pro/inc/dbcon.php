<?php 

$localhost = "Localhost";
$username = "root";
$password = "";
$dbname = "php_api_pro";

$conn = mysqli_connect($localhost, $username, $password,$dbname);

if(!$conn){
    die("Error". mysqli_connect_error());
}

?>