<?php

class Category {
 
    // database connection and table name
    private $conn;
    private $table_name = "kategori";
 
    // object properties
    public $ID;
    public $katNavn;
 
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

	// read products
	function read($id) {
        $query = "SELECT * FROM " . $this->table_name;
        
        if($id != '-1') {
            // select query
            $query .= " WHERE kategori.ID = ".$id;
        }
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    function write($post) {
        $katNavn = $post['katNavn'];
        $query = "INSERT INTO " . $this->table_name . " (katNavn) VALUES " . 
        "('$katNavn');";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        return $stmt->execute();
    }

    function update($post) {
        $katNavn = $post['katNavn'];
        $id = $post['ID'];
        $query = "UPDATE " . $this->table_name . " SET katNavn = ". $katNavn ." WHERE ID = " .$id;

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }
}