<?php

class Logging {
 
    // Database forbindelse og tabel der skal kigges i
    private $conn;
    private $table_name = "logging";
 
    // Variable properties
    public $ID;
    public $UserID;
    public $Handling;
    public $Date;
    public $userName;

    // constructor med $db som database forbindelse
    public function __construct($db) {
        $this->conn = $db;
    }

	/**
     * Funktionen er til for at læse fra databasen
     * @param int $id Er det ID som det row vi gerne vil læse fra har
     * @param int $limiter Er en int vi giver for at limit hvor mange results vi vil have
     * @param int $offset Er en int vi giver for at vælge hvor i arrayet den skal starte med at læse fra
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
	function read($id, $limiter, $offset) {
        // select query
        $query = "SELECT *, user.name as userName FROM " . $this->table_name . " l INNER JOIN user ON user.ID = l.UserID";
        // Hvis $id ikke er -1, selecter vi fra databasen ud fra det supplied ID
        if($id != '-1') {
            $query .= " WHERE logging.UserID = ".$id;
        }
        // Disse bruges til at ændre rækkefølgen af de modtagede data, og at sætte hvor meget der bliver hentet, og hvor i tabellen der skal startes
        $query .= " ORDER BY Date DESC";
        $query .= " LIMIT $limiter";
        $query .= " OFFSET $offset";
        

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        // Returner den preparede statement for at lave check på den
        return $stmt;
    }
    /**
     * Funktionen er til for at skrive til databasen udfra de oplyste data i $post variablen som der blev modtaget
     * @param Object $post Er den post request som der skal håndteres og hvad data der skal indsættes i databasen
     * @return $stmt->execute() Som er resultatet fra vores query efter databasen har modtaget den, ellers returner $e som er en error
     */
    function write($post) {
        // Sætter de parametre vi får fra $post i nogle lokale variabler
        $UserID = $post['UserID'];
        $Handling = $post['Handling'];
        // Sql statement som bruges til at indsætte i databasen med de forventede parametre
        $query = "INSERT INTO " . $this->table_name . " (UserID,Handling) VALUES " . "('$UserID', '$Handling')";

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
}