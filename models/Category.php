<?php

class Category {
 
    // Database forbindelse og tabel der skal kigges i
    private $conn;
    private $table_name = "kategori";
 
    // Variable properties
    public $ID;
    public $katNavn;
 
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
        $query = "SELECT * FROM " . $this->table_name;
        // Checker om der skal hentes alle rows hvis id er -1, ellers hent et specifik row
        if($id != '-1') {
            // select query
            $query .= " WHERE kategori.ID = ".$id;
        }
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        // returner statement for at checke på det
        return $stmt;
    }
    
    /**
     * Funktionen er til for at skrive til databasen udfra de oplyste data i $post variablen som der blev modtaget
     * @param Object $post Er den post request som der skal håndteres og hvad data der skal indsættes i databasen
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function write($post) {
        // Sætter de parametre vi får fra $post i nogle lokale variabler
        $katNavn = $post['katNavn'];
        // Sql statement som bruges til at indsætte i databasen med de forventede parametre
        $query = "INSERT INTO " . $this->table_name . " (katNavn) VALUES " . 
        "('$katNavn');";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        try {
            // execute query
            return $stmt->execute();
        } catch (PDOException $e) {
            // Returner error hvis det sker
            return ($e);
        }
        
    }

    /**
     * Funktionen er til for at opdatere en eksisterende data row i databasen med de oplyste data i $post variablen som der blev modtaget
     * @param Object $post Er den post request som der skal håndteres og hvad data der skal bruges til at opdatere det valgte tabel row i databasen med
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function update($post) {
        // Sætter de parametre vi får fra $post i nogle lokale variabler
        $katNavn = $post['katNavn'];
        $id = $post['ID'];
        // Sql statement som bruges til at opdatere i databasen med de forventede parametre på det valgte ID
        $query = "UPDATE " . $this->table_name . " SET katNavn = '". $katNavn ."' WHERE ID = '" .$id."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // Checker hvis statementet fejler under prepare fasen, og returnerer fejl beskeden
        if(!$stmt) return $this->conn->errorInfo();

        // execute query
        return $stmt->execute();
    }

    /**
     * Funktionen er til for at slette en row i databasen som matcher det ID den modtager
     * @param int $id Er det ID objektet som skal slettes har i databasen
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function delete($id) {
        // Delete query statement til at slette det valgte ID i databasen
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;

        // Forbered query statement
        $stmt = $this->conn->prepare($query);

        // Execute query statement og returner det til check
        return $stmt->execute();
    }
}