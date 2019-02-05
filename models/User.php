<?php

class User {
 
    // database connection and table name
    private $conn;
    private $table_name = "user";
 
    // object properties
    public $ID;
    public $name;
    public $phoneNumber;
    public $address;
    public $cardID;
    public $usergruppe;
    public $title;
 
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // read products
	function read($id) {

        if($id == -1) {
            // select all query
            $query = "SELECT *, u.ID as ID, ug.ID as usergruppeID FROM " . $this->table_name . " u INNER JOIN usergruppe ug ON ug.ID = u.usergruppe";
        } else {
            // select query
            $query = "SELECT *, u.ID as ID, ug.ID as usergruppeID FROM " . $this->table_name . " u INNER JOIN usergruppe ug ON ug.ID = u.usergruppe WHERE u.ID = "."'$id'";
        }
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    function write($name, $number, $address, $cardId) {
        $brugernavn = strtolower($brugernavn);
        $query = "INSERT INTO " . $this->table_name . " ('name','phoneNumber','address','cardID') VALUES " . "($name, $number, $address, $cardId)";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    }

    function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }
}