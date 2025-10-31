<?php
    class DBTest {
        private $conn;
        
        public function __construct($db){
            $this->conn = $db;
        }
        public function checkConnection(){
            if($this->conn){
                return ["status"=> "success","messages"=>"Database connected successfully"];
            }else {
                return ["status"=> "error", "message"=> "Failed to connect to database"];
            }
        }
    }
    ?>