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
    
    function write($userID, $equipmentID, $dateStart, $expectedDateEnd, $actualDateEnd, $description, $udløbet) {
        $brugernavn = strtolower($brugernavn);
        $query = "INSERT INTO " . $this->table_name . " (userID,equipmentID,dateStart,expectedDateEnd,actualDateEnd,description,udløbet) VALUES " . "('$userID', '$equipmentID', '$dateStart', '$expectedDateEnd', '$actualDateEnd', '$description', '$udløbet')";
        
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