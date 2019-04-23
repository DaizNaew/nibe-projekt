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
include_once '../models/Certificate.php';
// Her ser vi om den request metode som bliver sendt til restAPIen er en GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = $_GET['id'];
    // Der hentes det tableID fra GET requested, dette gør det muligt at kigge i hver tabel, da der er 2 tabeller assigned denne model og endpoint
    $tblID = $_GET['tableid'];
    // Et array med navnene på tabellerne i databasen
    $nameArr = ["certifikat","kørekort"];

    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();
    // Assigner $Certificate til at være Certificate model med selve database forbindelsen som parametre
    $Certificate = new Certificate($db);
    // Kører read funktionen som er inde i Certificate modellen
    $stmt = $Certificate->read($id,$tblID);
    // Find antallet af rows med data som blev returneret
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){
        // Laver et nyt array af arrays for at store de returnerede datasæt
        $products_arr=array();
        $products_arr[$nameArr[$tblID]]=array();
        // Så længe der findes data i datasættet, så gør vi denne funktion
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // Derefter udpakkes $row så alle de variabler det indeholder nu er at findes som lokale variabler, så bliver arrayet bare populated med data
            extract($row);
            $product_item=array(
                "ID" => $ID,
                "type" => $type,
            );
            // Tager dataet og pusher til det array som blev lavet til at store selve datasættet
            array_push($products_arr[$nameArr[$tblID]], $product_item);
        }
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show Certificate returned dataset in json format
        echo json_encode($products_arr);
    }else{
    
        // set response code - 404 Not found
        http_response_code(404);
    
        // Fortæl brugeren at der ikke kunne findes den requestede Certificate
        echo json_encode(
            array("message" => "Ingen ". $nameArr[$tblID] ." fundet")
        );
    }
// Her ser vi om den request metode som bliver sendt til restAPIen er en POST request
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();
    // Assigner $Certificate til at være Certificate model med selve database forbindelsen som parametre
    $Certificate = new Certificate($db);
    // Kører write funktionen som er inde i Certificate modellen
    $stmt = $Certificate->write($_POST);
    // Hvis databasen siger at der blev indsat noget, så er det $stmt true
    if($stmt) {
        // set response code - 200 OK
        http_response_code(200);
        // Fortæl brugeren at der blev indsat datasættet i databasen
        echo json_encode(
            array("message" => "Indsatte element i databasen", "result" => 1)
        );

    } else {
        // set response code - 200 OK fordi Toft ikke kan håndtere et 404
        http_response_code(200);
    
        // Fortæl brugeren at der ikke kunne indsætte det nye datasæt i databasen
        echo json_encode(
            array("message" => "Kunne ikke element i databasen",
            "result" => 0,
            "error" => '')
        );
    }
}
?>