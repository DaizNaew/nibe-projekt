<?php

class Usergroup {
 
    // database connection and table name
    private $conn;
    private $table_name = "usergruppe";
 
    // object properties
    public $ID;
    public $title;
 
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

	// read products
	function read($id) {
        $query = "SELECT * FROM " . $this->table_name;
        
        if($id != '-1') {
            // select query
            $query .= " WHERE ID = ".$id;
        }
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    function write($post) {
        $title = $post['title'];
        $query = "INSERT INTO " . $this->table_name . " (title) VALUES " . 
        "('$title');";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        return $stmt->execute();
    }

    function update($post) {
        $title = $post['title'];
        $id = $post['ID'];
        $query = "UPDATE " . $this->table_name . " SET title = '". $title ."' WHERE ID = '" .$id."'";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }
}