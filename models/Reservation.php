<?php

class Reservation {
 
    // Database forbindelse og tabel der skal kigges i
    private $conn;
    private $table_name = "reservation";
 
    // Variable properties
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
    public $equipmentID;
    public $activeName;
    public $brandName;
    public $modelName;
    public $serialNumber;
    public $assetTag;
    public $condition;
    public $categoryID;
    public $categoryName;
    public $inHouse;
    public $reserved;

    // Loan Props
    public $udløbet;
 
    // constructor med $db som database forbindelse
    public function __construct($db) {
        $this->conn = $db;
    }

	// read products
	/**
     * Funktionen er til for at læse fra et row i databasen
     * @param int $id Er det ID som det row vi gerne vil læse fra har
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
	function read($id) {
        // select query statement
        $query = "SELECT reservation.ID as ID,
		user.name as userName, user.phoneNumber as userPhoneNummer, user.cardID as userCardID, 
        e.ID as equipmentID,
		e.`aktivNavn` as equipmentNavn, e.assetTag as equipmentAsset, e.brand as equipmentBrand, e.model as equipmentModel, e.serieNummer as equipmentSerieNummer, e.stand as equipmentCondition,
		k.katNavn as categoryName,
		date_format(dateStart,'%d-%m-%Y %H:%i:%s ') as dateStart, date_format(expectedDateEnd,'%d-%m-%Y %H:%i:%s ') as expectedDateEnd, date_format(actualDateEnd,'%d-%m-%Y %H:%i:%s ') as actualDateEnd
		FROM reservation
		INNER JOIN user ON user.ID = reservation.userID
		INNER JOIN equipment e ON e.ID = reservation.equipmentID
		INNER JOIN kategori k ON k.ID = e.katID
        WHERE reservation.ID = $id;";

        // Forbered query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        // returner statementet så der kan laves check på den
        return $stmt;
    }
    
    /**
     * Funktionen er til for at skrive til databasen udfra de oplyste data i $post variablen som der blev modtaget
     * @param Object $post Er den post request som der skal håndteres og hvad data der skal indsættes i databasen
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function write($post) {
        // Sætter de parametre vi får fra $post i nogle lokale variabler
        $userID = $post['userID'];
        $equipmentID = $post['equipmentID'];
        $dateStart = date("y-m-d", strtotime($post['dateStart']));
        
        // Checker hvis der er extra data sent med, hvis ja bliver de sat ind query statementet og sendt til databasen
        $note = null;
        $expectedDateEnd = null;
        if(isset($post['expectedDateEnd'])) $expectedDateEnd = date("y-m-d", strtotime($post['expectedDateEnd']));
        if(isset($post['note'])) $note = $post['note'];
        

        // Sql statement som bruges til at indsætte i databasen med de forventede parametre
        $query = "INSERT INTO " . $this->table_name . " (userID,equipmentID,dateStart,expectedDateEnd,note) VALUES " . "('$userID', '$equipmentID', '$dateStart', '$expectedDateEnd', '$note')";
        
        // Forbered query statement
        $stmt = $this->conn->prepare($query);
    
        // Prøv at opdatere equipment til at sige at det værktøj med det valgte id er reserveret
        try {
            $query2 = "UPDATE equipment SET reserved = '1' WHERE ID = ". $equipmentID;
            $stmt2 = $this->conn->prepare($query2);
            // execute queries
            $stmt2->execute();
            // og returner den første query så der kan checkes på den
            return $stmt->execute();
        } catch (PDOException $e) {
            // Returner error hvis der går noget galt
            return ($e);
        }
    }

    /**
     * Funktionen er til for at slette en row i databasen som matcher det ID den modtager
     * Denne funktion retter også til i equipment for at sørge for at den reservation der blev slettet, og så
     * sørger for at værktøjet kan reserveres igen
     * @param int $id Er det ID objektet som skal slettes har i databasen
     * @param int $equipmentID Er det ID som værktøjet der tilhører denne reservation har, det bruges til at sætte reserved=0 i databasen
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function delete($id, $equipmentID) {
        // Delete query statement med supplied ID
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;

        // Forbered query statement
        $stmt = $this->conn->prepare($query);

        // Prøv at opdatere equipment til at sige at det værktøj med det valgte id ikke længere er reserveret
        try {
            $query2 = "UPDATE equipment SET reserved = '0' WHERE ID = ". $equipmentID;
            $stmt2 = $this->conn->prepare($query2);
            // execute queries
            $stmt2->execute();
            // og returner den første query så der kan checkes på den
            return $stmt->execute();
        } catch (PDOException $e) {
            // Returner error hvis der går noget galt
            return ($e);
        }
    }
}