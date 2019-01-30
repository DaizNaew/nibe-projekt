<?php

class Equipment {
 
    // database connection and table name
    private $conn;
    private $table_name = "equipment";
 
    // object properties
    public $ID;
    public $activeName;
    public $brandName;
    public $modelName;
    public $serialNumber;
    public $assetTag;
    public $condition;
    public $categoryID;
    public $categoryName;
    public $inHouse;
 
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

	// read products
	function read($id) {
        $query = "SELECT *, equipment.ID AS ID FROM " . $this->table_name." INNER JOIN kategori k ON k.ID = equipment.katID";
        
        if($id != '-1') {
            // select query
            $query .= " WHERE equipment.ID = ".$id;
        }
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    function write($post) {
        $aktivNavn = $post['activeName'];
        $brand = $post['brandName'];
        $model = $post['modelName'];
        $serieNummer = $post['serialNumber'];
        $assetTag = $post['assetTag'];
        $stand = $post['condition'];
        $katID = $post['categoryID'];
        $inhouse = $post['inHouse'];
        $query = "INSERT INTO " . $this->table_name . " (aktivNavn,brand,model,serieNummer,assetTag,stand,katID,inhouse) VALUES " . 
        "('$aktivNavn', '$brand', '$model', '$serieNummer', '$assetTag', '$stand', '$katID', '$inhouse');";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        return $stmt->execute();
    }
}