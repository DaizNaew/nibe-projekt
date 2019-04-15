<?php

class Usergroup {
 
    // Database forbindelse og tabel der skal kigges i
    private $conn;
    private $table_name = "usergruppe";
 
    // Variable properties
    public $ID;
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
        // Select query statement
        $query = "SELECT * FROM " . $this->table_name;
        // Checker om der skal hentes alle rows hvis id er -1, ellers hent et specifik row
        if($id != '-1') {
            // Tilføjelse til select statementet for at se efter en specifik row i tabellen
            $query .= " WHERE ID = ".$id;
        }
        // Forbered query statement for at checke at det er en valid query
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
        // Sætter $title variablen til at være den title value vi får fra $post requested
        $title = $post['title'];
        // Insert query statement
        $query = "INSERT INTO " . $this->table_name . " (title) VALUES " . 
        // Vi valgte at splitte det her op, da det eneste der som sådan skal ændres er den næste linje, da det er den der bestemmer hvad der skal sættes ind
        "('$title');";
        
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
        $title = $post['title'];
        $id = $post['ID'];

        // Update query statement
        $query = "UPDATE " . $this->table_name . " SET title = '". $title ."' WHERE ID = '" .$id."'";

        // Forbered query statement for at checke at det er en valid query
        $stmt = $this->conn->prepare($query);

        // execute query og return den for at lave check på den
        return $stmt->execute();
    }

    /**
     * Funktionen er til for at slette en row i databasen som matcher det ID den modtager
     * @param int $id Er det ID objektet som skal slettes har i databasen
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function delete($id) {
        // Delete query statement
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;

        // Forbered query statement for at checke at det er en valid query
        $stmt = $this->conn->prepare($query);

        // execute query og return den for at lave check på den
        return $stmt->execute();
    }
}