<?php
// required headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 1000');
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../database.inc';
include_once '../models/Equipment.php';
// Her ser vi om den request metode som bliver sendt til restAPIen er en GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Checker om der bliver sat et limit med, hvis ikke sætter vi bare limit til 20k, ellers bruger vi det der blev sendt med
    if(empty($_GET['limit'])){
        $limit = 20000;
    }else{
        $limit = $_GET['limit'];
    }
    // Checker om der bliver sat et offset med, hvis ikke sætter vi bare offset til 0, ellers bruger vi det der blev sendt med
    if(empty($_GET['offset'])){
        $offset = 0;
    }else{
        $offset = $_GET['offset'];
    }
    $id = $_GET['id'];

    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();
    // Assigner $Equipment til at være Equipment model med selve database forbindelsen som parametre
    $equipment = new Equipment($db);
    // Kører read funktionen som er inde i Equipment modellen
    $stmt = $equipment->read($id, $limit, $offset);
    // Find antallet af rows med data som blev returneret
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){
        // Laver et nyt array af arrays for at store de returnerede datasæt
        $products_arr=array();
        $products_arr["records"]=array();
        // Så længe der findes data i datasættet, så gør vi denne funktion
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // Derefter udpakkes $row så alle de variabler det indeholder nu er at findes som lokale variabler, så bliver arrayet bare populated med data
            extract($row);
            $product_item=array(
                "ID" => $ID,
                "activeName" => $aktivNavn,
                "brandName" => $brand,
                "modelName" => $model,
                "serialNumber" => $serieNummer,
                "assetTag" => $assetTag,
                "condition" => $stand,
                "categoryID" => $katID,
                "categoryName" => $katNavn,
                "inHouse" => $inhouse,
                "reserved" => $reserved,
            );
            // Tager dataet og pusher til det array som blev lavet til at store selve datasættet
            array_push($products_arr["records"], $product_item);
        }
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show Equipment returned dataset in json format
        echo json_encode($products_arr);
    }else{
    
        // set response code - 404 Not found
        http_response_code(200);
    
        // Fortæl brugeren at der ikke kunne findes den requestede Equipment
        echo json_encode(
            array("message" => "Ingen værktøj fundet")
        );
    }
// Her ser vi om den request metode som bliver sendt til restAPIen er en POST request
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();
    // Assigner $Equipment til at være Equipment model med selve database forbindelsen som parametre
    $equipment = new Equipment($db);
    // Kører write funktionen som er inde i Equipment modellen
    $stmt = $equipment->write($_POST);
    // Hvis databasen siger at der blev indsat noget, så er det $stmt true
    if($stmt) {
        // set response code - 200 OK
        http_response_code(200);
        // Fortæl brugeren at der blev indsat datasættet i databasen
        echo json_encode(
            array("message" => "Insert blev fuldført", "result" => 1)
        );

    } else {
        // set response code - 200 OK fordi Toft ikke kan håndtere et 404
        http_response_code(200);
    
        // Fortæl brugeren at der ikke kunne indsætte det nye datasæt i databasen
        echo json_encode(
            array("message" => "Insert blev ikke fuldført",
            "result" => 0,
            "error" => '')
        );
    }
}