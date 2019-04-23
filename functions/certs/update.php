<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // database connection will be here
    include_once '../../database.inc';
    include_once '../../models/Certificate.php';

    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();
    // Table som der kan tjekkes igennem
    $table_name = ["certifikat","kørekort"];

    // Assigner $Certificate til at være Certificate model med selve database forbindelsen som parametre
    $Certificate = new Certificate($db);

    // Kører update funktionen som er inde i Certificate modellen
    $stmt = $Certificate->update($_POST);
    // Hvis databasen siger at der blev opdateret noget, så er det $stmt true
    if($stmt) {
        // set response code - 200 OK
        http_response_code(200);
        // Fortæl brugeren at der blev opdateret det valgte datasæt
        echo json_encode(
            array("message" => "Opdaterede element i databasen", "result" => 1)
        );

    } else {
        // set response code - 200 OK fordi Toft ikke kan håndtere et 404
        http_response_code(200);
    
        // Fortæl brugeren at der ikke kunne opdatere det valgte datasæt i databasen
        echo json_encode(
            array("message" => "Kunne ikke element Kategori i databasen",
            "result" => 0,
            "error" => '')
        );
    }