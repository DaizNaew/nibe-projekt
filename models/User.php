<?php

class User {
 
    // database connection and table name
    private $conn;
    private $table_name = "user";
 
    // object properties
    public $ID;
    public $name;
    public $phoneNumber;
    public $address;
    public $cardID;
    public $brugernavn;
    public $usergruppe;
    public $title;
 
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

	// read products
	function read_uname($uname) {

        $uname = strtolower($uname);

        if($uname == -1) {
            // select all query
            $query = "SELECT *, u.ID as ID, ug.ID as usergruppeID FROM " . $this->table_name . " u INNER JOIN usergruppe ug ON ug.ID = u.usergruppe";
        } else {
            // select query
            $query = "SELECT *, u.ID as ID, ug.ID as usergruppeID FROM " . $this->table_name . " u INNER JOIN usergruppe ug ON ug.ID = u.usergruppe WHERE brugernavn = "."'$uname'";
        }
    
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
    
        // execute query
        $stmt->execute();
    }

    function checkPass($pass,$username) {
        $username = strtolower($username);
        $query = "SELECT * FROM " . $this->table_name . " WHERE brugernavn = '$username'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();

        if(!$stmt->fetch()) {
            
            $products_arr["user"]=array();
            $product_item=array(
                "ID" => -1,
                "usergruppe" => -1
            );
            array_push($products_arr["user"], $product_item);
            print_r (json_encode($products_arr));
            return false;
        } else {
            $products_arr["user"]=array();
            $stmt->closeCursor();
            $query = "SELECT u.ID, name, usergruppe, title FROM " . $this->table_name . " u INNER JOIN usergruppe ug ON ug.ID = usergruppe WHERE adgangskode = '$pass'";
             // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $temp = $stmt->execute();
            if(!$stmt->fetch()) {
                $products_arr["user"]=array();
                $product_item=array(
                    "ID" => -2,
                    "usergruppe" => -2
                );
                array_push($products_arr["user"], $product_item);
                print_r (json_encode($products_arr));
                return false;
            }
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $product_item=array(
                    "ID" => $ID,
                    "name" => $name,
                    "usergruppe" => $usergruppe,
                    "usergruppetitle" => $title, 
                );
                array_push($products_arr["user"], $product_item);
            
            return $products_arr;
        }
    }
    function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ".$id;

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }
}