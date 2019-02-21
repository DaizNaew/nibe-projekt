<?php

class Logging {
 
    // database connection and table name
    private $conn;
    private $table_name = "logging";
 
    // object properties
    public $ID;
    public $UserID;
    public $Handling;
    public $Date;
    public $userName;

    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

	// read products
	function read($id) {
        $query = "SELECT *, user.name as userName FROM " . $this->table_name . " l INNER JOIN user ON user.ID = l.UserID";
        
        if($id != '-1') {
            // select query
            $query .= " WHERE logging.ID = ".$id;
        }
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    function write($post) {
        $UserID = $post['UserID'];
        $Handling = $post['Handling'];

        $query = "INSERT INTO " . $this->table_name . " (UserID,Handling) VALUES " . "('$UserID', '$Handling')";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        try {
            // execute query
            return $stmt->execute();
        } catch (PDOException $e) {
            return ($e);
        }
    }
}