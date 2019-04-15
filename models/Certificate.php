<?php

class Certificate {
 
    // Database forbindelse og tabel der skal kigges i
    private $conn;
    private $table_name = ["certifikat","kørekort"];
 
    // Variable properties
    public $ID;
    public $type;
 
    // constructor med $db som database forbindelse
    public function __construct($db) {
        $this->conn = $db;
    }

	/**
     * Funktionen er til for at læse fra et row i databasen
     * @param int $id Er det ID som det row vi gerne vil læse fra har
     * @param int $tblID Er et ID som bruges til at specificere hvilken tabel der skal læses fra
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
	function read($id,$tblID) {
        // select query statement
        $query = "SELECT * FROM " . $this->table_name[$tblID];
        // Checker om der skal hentes alle rows hvis id er -1, ellers hent et specifik row
        if($id != '-1') {
            // select query
            $query .= " WHERE kategori.ID = ".$id;
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
        $type = $post['type'];
        $tblID = $post['tableID'];
        // Sql statement som bruges til at indsætte i databasen med de forventede parametre
        $query = "INSERT INTO " . $this->table_name[$tblID] . " (type) VALUES " . 
        "('$type');";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // Prøver at execute statement queriet for at se om der bliver meldt fejl af databasen
        try {
            // execute query
            return $stmt->execute();
        } catch (PDOException $e) {
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
        $type = $post['type'];
        $id = $post['ID'];
        $tblID = $post['tableID'];
        // Sql statement som bruges til at opdatere i databasen med de forventede parametre på det valgte ID
        $query = "UPDATE " . $this->table_name[$tblID] . " SET type = '". $type ."' WHERE ID = '" .$id."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        return $stmt->execute();
    }
    
    /**
     * Funktionen er til for at slette en row i databasen som matcher det ID den modtager
     * Denne funktion kan slette i både kørekort og certifikater, ud fra det supplied table ID
     * @param int $id Er det ID objektet som skal slettes har i databasen
     * @param int $tblID Er et ID som bruges til at specificere hvilken tabel der skal slettes i
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function delete($id,$tblID) {
        // Delete query statement til at slette det valgte ID i databasen
        $query = "DELETE FROM " . $this->table_name[$tblID] . " WHERE ID = ".$id;
        // Forbered query statement
        $stmt = $this->conn->prepare($query);
        // Execute query statement og returner det til check
        return $stmt->execute();
    }
}