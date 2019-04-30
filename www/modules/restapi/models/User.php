<?php

class User {
 
    // Database forbindelse og tabel der skal kigges i
    private $conn;
    private $table_name = "user";
 
    // Variable properties
    public $ID;
    public $name;
    public $phoneNumber;
    public $address;
    public $cardID;
    public $usergruppe;
    public $title;
 
    // constructor med $db som database forbindelse
    public function __construct($db) {
        $this->conn = $db;
    }

	/**
     * Funktionen er til for at læse fra et row i databasen
     * @param int $id Er det ID som det row vi gerne vil læse fra har
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
	function read($id) {

        // select query statement
        $query = "SELECT *, u.ID as ID, ug.ID as usergruppeID FROM " . $this->table_name . " u INNER JOIN usergruppe ug ON ug.ID = u.usergruppe";

        // Checker om der skal hentes alle rows hvis id er -1, ellers hent et specifik row
        if($id != '-1') {
            // Tilføjelse til select statementet for at se efter en specifik row i tabellen
            $query .= " WHERE u.ID = "."'$id'";
        }
        // Forbered query statement for at checke at det er en valid query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        // Returner den forberedte statement for at lave check på den
        return $stmt;
    }

    function getByCard($id) {
        
        // select query statement
        $query = "SELECT *, u.ID as ID, ug.ID as usergruppeID FROM " . $this->table_name . " u INNER JOIN usergruppe ug ON ug.ID = u.usergruppe WHERE u.cardID = "."'$id'";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        // Returner den forberedte statement for at lave check på den
        return $stmt;
    }
    
    /**
     * Funktionen er til for at skrive til databasen udfra de oplyste data i $post variablen som der blev modtaget
     * @param Object $post Er den post request som der skal håndteres og hvad data der skal indsættes i databasen
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function write($post) {

        // Sætter variabler til at være de informationer vi får fra $post requested
        $name = $post['name'];
        $number = $post['phoneNumber'];
        $address = $post['address'];
        $cardId = $post['cardID'];
        $usergruppe = $post['usergruppe'];

        // Insert query statement
        $query = "INSERT INTO " . $this->table_name . " (name,phoneNumber,address,cardID,usergruppe) VALUES " . 
        // Vi valgte at splitte det her op, da det eneste der som sådan skal ændres er den næste linje, da det er den der bestemmer hvad der skal sættes ind
        "('$name', '$number', '$address', '$cardId', '$usergruppe')";
        
        // Forbered query statement for at checke at det er en valid query
        $stmt = $this->conn->prepare($query);

        // Prøver at execute statement queriet for at se om der bliver meldt fejl af databasen
        try {
            // execute query og return den for at lave check på den
            return $stmt->execute();
        } catch (PDOException $e) {
            // Returner error koden så der kan laves håndtering på den
            return ($e);
        }
    }

    /**
     * Funktionen er til for at slette en row i databasen som matcher det ID den modtager
     * @param int $id Er det ID objektet som skal slettes har i databasen
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function delete($id) {

        // Der gøres alt det her for at sikre os at brugeren får slettet alle sine certifikater før end vi prøver at slette brugeren
        $qc = "DELETE FROM user_certifikat WHERE userID =".$id;
        $sc = $this->conn->prepare($qc);
        $sc->execute();

        // Der gøres alt det her for at sikre os at brugeren får slettet alle sine kørekort før end vi prøver at slette brugeren
        $qk = "DELETE FROM user_kørekort WHERE userID =".$id;
        $sk = $this->conn->prepare($qk);
        $sk->execute();

        // Delete query statement
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;
        
        // Forbered query statement for at checke at det er en valid query
        $stmt = $this->conn->prepare($query);
        
        // Prøver at execute statement queriet for at se om der bliver meldt fejl af databasen
        try {
            // execute query og return den for at lave check på den
            return $stmt->execute();
        } catch (PDOException $e) {
            // Returner error koden så der kan laves håndtering på den
            return ($e);
        }
    }

    /**
     * Funktionen er til for at opdatere en eksisterende data row i databasen med de oplyste data i $post variablen som der blev modtaget
     * @param Object $post Er den post request som der skal håndteres og hvad data der skal bruges til at opdatere det valgte tabel row i databasen med
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function update($post) {

        // Sætter variabler til at være de informationer vi får fra $post requested
        $name = $post['name'];
        $phoneNumber = $post['phoneNumber'];
        $address = $post['address'];
        $cardID = $post['cardID'];
        $usergruppe = $post['usergruppe'];
        $id = $post['ID'];

        // Update query statement
        $query = "UPDATE " . $this->table_name . " SET name = '" . $name . "', phoneNumber = '" . $phoneNumber . "', address = '" . $address . "', cardID = '" . $cardID . "', usergruppe = '" . $usergruppe . "' WHERE ID = '" .$id."'";
        
        // Forbered query statement for at checke at det er en valid query
        $stmt = $this->conn->prepare($query);

        // Prøver at execute statement queriet for at se om der bliver meldt fejl af databasen
        try {
            // execute query og return den for at lave check på den
            return $stmt->execute();
        } catch (PDOException $e) {
            // Returner error koden så der kan laves håndtering på den
            return ($e);
        }
    }
}