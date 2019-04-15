<?php

class Loaned {
 
    // Database forbindelse og tabel der skal kigges i
    private $conn;
    private $table_name = "udlånt";
 
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
        $query = "SELECT *, udlånt.ID as ID FROM " . $this->table_name." INNER JOIN equipment e ON e.ID = udlånt.equipmentID INNER JOIN kategori k ON k.ID = e.katID";
        // Checker om der skal hentes alle rows hvis id er -1, ellers hent et specifik row
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
    
    /**
     * Funktionen er til for at skrive til databasen udfra de oplyste data i $post variablen som der blev modtaget
     * @param Object $post Er den post request som der skal håndteres og hvad data der skal indsættes i databasen
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function write($post) {
        // Sætter de parametre vi får fra $post i nogle lokale variabler
        $userID = $post['userID'];
        $equipmentID = $post['equipmentID'];

        $expectedDateEnd = null;
        $note = null;
        if(isset($post['expectedDateEnd'])) $expectedDateEnd = $post['expectedDateEnd'];
        if(isset($post['note'])) $note = $post['note'];
        // Sql statement som bruges til at indsætte i databasen med de forventede parametre
        $query = "INSERT INTO " . $this->table_name . " (userID,equipmentID,expectedDateEnd,note) VALUES " . "('$userID', '$equipmentID', '$expectedDateEnd', '$note')";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // Prøver at execute statement queriet for at se om der bliver meldt fejl af databasen
        try {
            // Update statement til at sætte værktøjet som allerede udlånt i databasen
            $query2 = "UPDATE equipment SET inhouse = '0' WHERE ID = ". $equipmentID;
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();
            // execute query
            return $stmt->execute();
        } catch (PDOException $e) {
            return ($e);
        }
    }

    /**
     * Funktionen er til for at slette en row i databasen som matcher det ID den modtager
     * Denne funktion retter også til i equipment for at sørge for at det lån der blev slettet, og så
     * sørger for at værktøjet kan lånes igen
     * @param int $id Er det ID objektet som skal slettes har i databasen
     * @param int $equipmentID Er det ID som værktøjet der tilhører dette loan har, det bruges til at sætte inhouse=1 i databasen
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function delete($id, $equipmentID) {
        // Delete query statement til at slette det valgte ID i databasen
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;
        // Forbered query statement
        $stmt = $this->conn->prepare($query);
        // Prøv og opdater værktøjet i databasen til at stå som værende ledigt til udlån igen
        try {
            // Update statement til at sætte værktøjet som klar til udlån igen i databasen
            $query2 = "UPDATE equipment SET inhouse = '1' WHERE ID = ". $equipmentID;
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();
        // Execute query statement og returner det til check
            return $stmt->execute();
        } catch (PDOException $e) {
            return ($e);
        }
    }

    function deliver($post) {
        // Sætter de parametre vi får fra $post i nogle lokale variabler
        $id = $post['id'];
        $equipmentID = $post['equipmentID'];
        

        //Henter og sætter den nuværende dato og tid
        date_default_timezone_set('Europe/Copenhagen'); 

        $date = date("m/d/Y h:i A");
        $final = strtotime($date);
        $time_posted = date("Y-m-d H:i:s", $final);
        // Sql statement som bruges til at opdatere i databasen med de forventede parametre på det valgte ID
        $query = "UPDATE " . $this->table_name . " SET actualDateEnd = '" . $time_posted . "', udløbet = '1' WHERE equipmentID = " . $equipmentID;

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // Prøver at execute statement queriet for at se om der bliver meldt fejl af databasen
        try {
            // Update statement til at sætte værktøjet som klar til udlån igen i databasen
            $query2 = "UPDATE equipment SET inhouse = '1' WHERE ID = ". $equipmentID;
            $stmt2 = $this->conn->prepare($query2);
            // Execute query
            $stmt2->execute();
            // Execute query statement og returner det til check
            return $stmt->execute();
        } catch (PDOException $e) {
            return ($e);
        }
    }
}