<?php
class Database{
    private $host = 'localhost';
    private $port = 3307;
    private $db_name = 'surf_shcool';
    private $user_name = 'tester';
    private $password = '123';

    public function connect(){
        try{
            $pdo= new PDO('mysql:host='.$this -> host .';port='.$this -> port.';dbname='.$this -> db_name,$this->user_name,$this-> password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            echo "connected";
        }catch(PDOException $e){
            die('connection failed: ' . $e->getMessage());
        }
        return $pdo;
    }
}
$connectDb = new Database();
$connectDb->connect();
?>