<?php 
$host = "localhost";
$dbName ="gearlog";
$userName="root";
$password="";

try{
    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbName", $userName, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<p class="connectMsg" style="color:green">Connected Successfully!</p>';
}
catch(PDOException $e){
    die("Connection failed: " . $e->getMessage());
}



?>