<?php
 class Lpdo{

    public $db;
    public $dbname;
    public $query;
    public $request;
    public $result;


    public function __construct($host, $username, $password, $db){

    $this->db = mysqli_connect($host, $username, $password, $db);

    $this->dbname = $db;
    return $this->db;

    }

    public function connect($host, $username, $password, $db){
       if(isset($this->$db)){
        unset($this->db);
       }
       else{
        $this->db = mysqli_connect($host, $username, $password, $db);

        $this->dbname = $db;
        return $this->db;
       }

    }
    public function destructor(){
        $this->db = null;
        // var_dump($this->db);
    }
    public function close(){
        $this->db = null;
        var_dump($this->db);
    }
    public function execute($query){

        $this->request = $query;
        $this->query = mysqli_query($this->db, $this->request);
        $this->result = mysqli_fetch_all($this->query);
        return $this->result;
    }
    public function getLastQuery(){
        if(isset($this->request)){
        return $this->request;
        }else{
            return false;
        }
    }
    public function getLastResult(){
        if(isset($this->result)){
            return $this->result;
        }else{
            return false;
        }

    }
    public function getTables(){
        $this->request = 'show tables';
        $this->query = mysqli_query($this->db, $this->request);
        $this->result = mysqli_fetch_all($this->query);
        return $this->result;
    }
    public function getFields($table){
        $this->request = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND TABLE_SCHEMA='classes'";
        $this->query = mysqli_query($this->db, $this->request);
        $this->result = mysqli_fetch_all($this->query);
        return $this->result;
    }

 }

?>
