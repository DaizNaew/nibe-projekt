<?php

class Reservation {
 
    // database connection and table name
    private $conn;
    private $table_name = "reservation";
 
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
        $query = "SELECT reservation.ID as ID,
		user.name as userName, user.phoneNumber as userPhoneNummer, user.cardID as userCardID, 
		e.`aktivNavn` as equipmentNavn, e.assetTag as equipmentAsset, e.brand as equipmentBrand, e.model as equipmentModel, e.serieNummer as equipmentSerieNummer, e.stand as equipmentCondition,
		k.katNavn as equipmentKategori,
		date_format(dateStart,'%d-%m-%Y %H:%i:%s ') as dateStart, date_format(expectedDateEnd,'%d-%m-%Y %H:%i:%s ') as expectedDateEnd, date_format(actualDateEnd,'%d-%m-%Y %H:%i:%s ') as actualDateEnd
		FROM reservation
		INNER JOIN user ON user.ID = reservation.userID
		INNER JOIN equipment e ON e.ID = reservation.equipmentID
		INNER JOIN kategori k ON k.ID = e.katID
        WHERE ID = $id;";
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
}