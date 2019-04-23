<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../database.inc';
include_once '../models/Loaned.php';
// Her ser vi om den request metode som bliver sendt til restAPIen er en GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();
    // Assigner $Loaned til at være Loaned model med selve database forbindelsen som parametre
    $Loaned = new Loaned($db);
    // Kører read funktionen som er inde i Loaned modellen
    $stmt = $Loaned->read($id);
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
                "userID" => $userID,
                "equipmentID" => $equipmentID,
                "dateStart" => $dateStart,
                "expectedDateEnd" => $expectedDateEnd,
                "note" => $note,
                "udløbet" => $udløbet,
                "aktivNavn" => $aktivNavn,
                "brand" => $brand,
                "model" => $model,
                "serieNummer" => $serieNummer,
                "assetTag" => $assetTag,
                "stand" => $stand,
                "katID" => $katID,
                "inhouse" => $inhouse,
                "reserved" => $reserved,
                "katNavn" => $katNavn,
            );
            // Tager dataet og pusher til det array som blev lavet til at store selve datasættet
            array_push($products_arr["records"], $product_item);
        }
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show loans returned dataset in json format
        echo json_encode($products_arr);
    }else{
    
        // set response code - 404 Not found
        http_response_code(404);
    
        // Fortæl brugeren at der ikke kunne findes den requestede loan
        echo json_encode(
            array("message" => "Ingen loan fundet")
        );
    }
// Her ser vi om den request metode som bliver sendt til restAPIen er en POST request
} else  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();
    // Assigner $Loaned til at være Loaned model med selve database forbindelsen som parametre
    $Loaned = new Loaned($db);
    // Kører write funktionen som er inde i Loaned modellen
    $stmt = $Loaned->write($_POST);
    // Hvis databasen siger at der blev indsat noget, så er det $stmt true
    if($stmt === true) {
        // set response code - 200 OK
        http_response_code(200);
        // Fortæl brugeren at der blev indsat datasættet i databasen
        echo json_encode(
            array("message" => "Indsatte loan i databasen", "result" => 1)
        );

    } else {
        // set response code - 200 OK fordi Toft ikke kan håndtere et 404
        http_response_code(200);
    
        // Fortæl brugeren at der ikke kunne indsætte det nye datasæt i databasen
        echo json_encode(
            array("message" => "Kunne ikke loan i databasen",
            "result" => 0,
            "error" => $stmt->getMessage())
        );
    }
}