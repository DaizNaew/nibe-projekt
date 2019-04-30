<?php
// required headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 1000');
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../database.inc';
include_once '../models/User.php';
// Her ser vi om den request metode som bliver sendt til restAPIen er en GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();

    // Assigner $user til at være user model med selve database forbindelsen som parametre
    $user = new User($db);

    // Kører read funktionen som er inde i user modellen
    $stmt = $user->read($id);

    // Find antallet af rows med data som blev returneret
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){
        // Laver et nyt array af arrays for at store de returnerede datasæt
        $user_arr=array();
        $user_arr["records"]=array();
        // Så længe der findes data i datasættet, så gør vi denne funktion
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // Derefter udpakkes $row så alle de variabler det indeholder nu er at findes som lokale variabler, så bliver arrayet bare populated med data
            extract($row);
            $user_item=array(
                "ID" => $ID,
                "name" => $name,
                "phoneNumber" => $phoneNumber,
                "address" => $address,
                "cardID" => $cardID,
                "usergruppe" => $usergruppe,
                "usergruppetitle" => $title,
            );
            // Tager dataet og pusher til det array som blev lavet til at store selve datasættet
            array_push($user_arr["records"], $user_item);
        }
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show user returned dataset in json format
        echo json_encode($user_arr);
    }else{
    
        // set response code - 404 Not found
        http_response_code(404);
    
        // Fortæl brugeren at der ikke kunne findes den requestede bruger
        echo json_encode(
            array("message" => "Ingen brugere fundet", "result" => 0)
        );
    }
// Her ser vi om den request metode som bliver sendt til restAPIen er en POST request
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();
    // Assigner $User til at være User model med selve database forbindelsen som parametre
    $User = new User($db);
    // Kører write funktionen som er inde i User modellen
    $stmt = $User->write($_POST);
    // Hvis databasen siger at der blev indsat noget, så er det $stmt true
    if($stmt === true) {
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
            array("message" => "Kunne ikke indsætte i databasen",
            "result" => 0,
            "error" => $stmt->getMessage(),
            "errMessage" => `Brugeren `.$_POST['name'].` findes allerede i databasen`)
        );
    }
}