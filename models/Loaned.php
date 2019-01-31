<?php

class Equipment {
 
    // database connection and table name
    private $conn;
    private $table_name = "equipment";
 
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
        $query = "SELECT * FROM " . $this->table_name." INNER JOIN kategori k ON k.ID = equipment.ID";
        
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
    
    function write($name, $number, $address, $cardId, $brugernavn, $adgangskode) {
        $brugernavn = strtolower($brugernavn);
        $query = "INSERT INTO " . $this->table_name . " ('name','phoneNumber','address','cardID','brugernavn','adgangskode') VALUES " . "($name, $number, $address, $cardId, $brugernavn, $adgangskode)";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    }

    function checkPass($pass,$username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE brugernavn = '$username'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();

        if(!$stmt->fetch()) {
            
            $products_arr["user"]=array();
            $product_item=array(
                "ID" => -1,
                "usergruppe" => -1
            );
            array_push($products_arr["user"], $product_item);
            echo json_encode($products_arr);
            return;
        } else {
            $products_arr["user"]=array();
            $stmt->closeCursor();
            $query = "SELECT * FROM " . $this->table_name . " WHERE adgangskode = '$pass'";
             // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $temp = $stmt->execute();
            if(!$stmt->fetch()) { return false; }
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $product_item=array(
                    "ID" => $ID,
                    "usergruppe" => $usergruppe
                );
                array_push($products_arr["user"], $product_item);
            
            return $products_arr;
        }
    }
    function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }
}