<?php

class Certificate {
 
    // database connection and table name
    private $conn;
    private $table_name = ["certifikat","kørekort"];
 
    // object properties
    public $ID;
    public $type;
 
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

	// read products
	function read($id,$tblID) {
        $query = "SELECT * FROM " . $this->table_name[$tblID];
        
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
        $type = $post['type'];
        $tblID = $post['tableID'];
        $query = "INSERT INTO " . $this->table_name[$tblID] . " (type) VALUES " . 
        "('$type');";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        try {
            // execute query
            return $stmt->execute();
        } catch (PDOException $e) {
            return ($e);
        }
    }

    function update($post) {
        $type = $post['type'];
        $id = $post['ID'];
        $tblID = $post['tableID'];
        $query = "UPDATE " . $this->table_name[$tblID] . " SET type = '". $type ."' WHERE ID = '" .$id."'";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    function delete($id,$tblID) {
        $query = "DELETE FROM " . $this->table_name[$tblID] . " WHERE ID = ".$id;

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }
}