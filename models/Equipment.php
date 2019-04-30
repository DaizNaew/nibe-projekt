<?php

class Equipment {
 
    // Database forbindelse og tabel der skal kigges i
    private $conn;
    private $table_name = "equipment";
 
    // Variable properties
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
    public $reserved;
 
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
        // select query statement
        $query = "SELECT *, equipment.ID AS ID FROM " . $this->table_name." INNER JOIN kategori k ON k.ID = equipment.katID";
        // Checker om der skal hentes alle rows hvis id er -1, ellers hent et specifik row
        if($id != '-1') {
            // select query
            $query .= " WHERE equipment.ID = ".$id;
        }
        // Disse bruges til at ændre rækkefølgen af de modtagede data, og at sætte hvor meget der bliver hentet, og hvor i tabellen der skal startes
        $query .= " ORDER BY assetTag ASC";
        $query .= " LIMIT $limiter";
        $query .= " OFFSET $offset";
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
        $aktivNavn = $post['activeName'];
        if(!isset($post['brandName'])) {
            $brand = "";
        } else {
            $brand = $post['brandName'];
        }
        
        if(!isset($post['modelName'])) {
            $model = "";
        } else {
            $model = $post['modelName'];
        }

        if(!isset($post['serialNumber'])) {
            $serieNummer = "";
        } else {
            $serieNummer = $post['serialNumber'];
        }

        $assetTag = $post['assetTag'];
        $stand = $post['condition'];
        $katID = $post['categoryID'];
        // Sql statement som bruges til at indsætte i databasen med de forventede parametre
        $query = "INSERT INTO " . $this->table_name . " (aktivNavn,brand,model,serieNummer,assetTag,stand,katID,inhouse) VALUES " . 
        "('$aktivNavn', '$brand', '$model', '$serieNummer', '$assetTag', '$stand', '$katID', '1');";
        
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
        $aktivNavn = $post['activeName'];
        $brand = $post['brandName'];
        $model = $post['modelName'];
        $serieNummer = $post['serialNumber'];
        $assetTag = $post['assetTag'];
        $stand = $post['condition'];
        $katID = $post['categoryID'];
        $id = $post['ID'];
        // Sql statement som bruges til at opdatere i databasen med de forventede parametre på det valgte ID
        $query = "UPDATE " . $this->table_name . " SET aktivNavn = '" . $aktivNavn . "', brand = '" . $brand . "', model = '" . $model . "', serieNummer = '" . $serieNummer . "', assetTag = '" . $assetTag . "', stand = '" . $stand . "', katID = '" . $katID . "' WHERE ID = '" .$id."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
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