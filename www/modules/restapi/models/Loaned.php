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
    public $note;

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
        $query = "SELECT *, udlånt.ID as ID FROM " . $this->table_name." INNER JOIN equipment e ON e.ID = udlånt.equipmentID INNER JOIN kategori k ON k.ID = e.katID";
        
        if($id == -1) {
        } else {
            // select query
            $query .= " WHERE udlånt.ID = ".$id;
        }
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    function write($post) {

        $userID = $post['userID'];
        $equipmentID = $post['equipmentID'];

        $expectedDateEnd = null;
        $note = null;
        if(isset($post['expectedDateEnd'])) $expectedDateEnd = $post['expectedDateEnd'];
        if(isset($post['note'])) $note = $post['note'];

        $query = "INSERT INTO " . $this->table_name . " (userID,equipmentID,expectedDateEnd,note) VALUES " . "('$userID', '$equipmentID', '$expectedDateEnd', '$note')";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        try {
            $query2 = "UPDATE equipment SET inhouse = '0' WHERE ID = ". $equipmentID;
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();
            // execute query
            return $stmt->execute();
        } catch (PDOException $e) {
            return ($e);
        }
    }

    function delete($id, $equipmentID) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;

        $stmt = $this->conn->prepare($query);

        try {
            $query2 = "UPDATE equipment SET inhouse = '1' WHERE ID = ". $equipmentID;
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();
            // execute query
            return $stmt->execute();
        } catch (PDOException $e) {
            return ($e);
        }
    }

    function deliver($post) {
        $id = $post['id'];
        $equipmentID = $post['equipmentID'];
        

        date_default_timezone_set('Europe/Copenhagen'); 

        $date = date("m/d/Y h:i A");
        $final = strtotime($date);
        $time_posted = date("Y-m-d H:i:s", $final);

        $query = "UPDATE " . $this->table_name . " SET actualDateEnd = '" . $time_posted . "', udløbet = '1' WHERE equipmentID = " . $equipmentID;

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        try {
            $query2 = "UPDATE equipment SET inhouse = '1' WHERE ID = ". $equipmentID;
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();
            // execute query
            return $stmt->execute();
        } catch (PDOException $e) {
            return ($e);
        }
    }
}