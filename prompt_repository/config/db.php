<?php
$server="localhost";
$port=3307;
$user_name="tester";
$password="123";
$db_name="prompt_repository";

try{
    $pdo= new PDO("mysql:host=$server;port=$port;dbname=$db_name;charset=utf8;",$user_name,$password);
    $pdo-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    echo "<span style='color:green;'>connected successfully</span>";
}catch(PDOException $e){
    die('<span style="color:red;">Failed to connect: ' . $e->getMessage() . '</span>');
}
?>