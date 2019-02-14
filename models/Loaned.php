<?php

class Loaned {
 
    // database connection and table name
    private $conn;
    private $table_name = "udlånt";
 
    // object properties
    public $ID;

    // User properties
    public $userID;
    public $name;
    public $phoneNumber;
    public $address;
    public $cardID;
    public $brugernavn;
    public $usergruppe;

    // Loan  Props
    public $dateStart;
    public $expectedDateEnd;
    public $actualDateEnd;
    public $description;

    // Tool props
    public $toolID;
    public $activeName;
    public $brandName;
    public $modelName;
    public $serialNumber;
    public $assetTag;
    public $condition;
    public $categoryID;
    public $categoryName;
    public $inHouse;

    // Loan Props
    public $udløbet;
 
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

	// read products
	function read($id) {
        $query = "SELECT *, udlånt.ID AS ID, equipment FROM " . $this->table_name." INNER JOIN kategori k ON k.ID = equipment.katID";
        
        if($id == -1) {
        } else {
            // select query
            $query .= " WHERE equipment.ID = ".$id;
        }
        print_r($query);
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    function write($post) {

        $userID = $post['userID'];
        $equipmentID = $post['equipmentID'];
        $expectedDateEnd = $post['expectedDateEnd'];
        $description = $post['description'];

        $brugernavn = strtolower($brugernavn);
        $query = "INSERT INTO " . $this->table_name . " (userID,equipmentID,expectedDateEnd,description) VALUES " . "('$userID', '$equipmentID', '$expectedDateEnd', '$description')";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        try {
            // execute query
            return $stmt->execute();
        } catch (PDOException $e) {
            return ($e);
        }
    }

    function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    function deliver($id, $deliverDate) {
        $query = "UPDATE " . $this->table_name . " SET actualDateEnd = " . $deliverDate . ", udløbet = 1 WHERE ID = " . $id;
    }
}