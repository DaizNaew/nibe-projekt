<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // database connection will be here
    include_once '../../database.inc';
    include_once '../../models/Loaned.php';

    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();

    // Assigner $Loaned til at være Loaned model med selve database forbindelsen som parametre
    $Loaned = new Loaned($db);
    
    // Kører deliver funktionen som er inde i Loaned modellen
    $stmt = $Loaned->deliver($_POST);
    // Hvis databasen siger at der blev afleveret noget, så er det $stmt true
    if($stmt === true) {
        // set response code - 200 OK
        http_response_code(200);
        // Fortæl brugeren at der blev afleveret det valgte datasæt
        echo json_encode(
            array("message" => "Afleverede loan i databasen", "result" => 1)
        );

    } else {
        // set response code - 200 OK fordi Toft ikke kan håndtere et 404
        http_response_code(200);
    
        // Fortæl brugeren at der ikke kunne afleveret det valgte datasæt i databasen
        echo json_encode(
            array("message" => "Kunne ikke Aflevere loan i databasen",
            "result" => 0,
            "error" => $stmt->getMessage())
        );
    }