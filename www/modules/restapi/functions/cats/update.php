<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // database connection will be here
    include_once '../../database.inc';
    include_once '../../models/Category.php';

    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();

    // Assigner $category til at være category model med selve database forbindelsen som parametre
    $category = new Category($db);

    // Kører update funktionen som er inde i category modellen
    $stmt = $category->update($_POST);
    
    // Hvis databasen siger at der blev opdateret noget, så er det $stmt true
    if($stmt) {
        // set response code - 200 OK
        http_response_code(200);
        // Fortæl brugeren at der blev opdateret det valgte datasæt
        echo json_encode(
            array("message" => "Opdaterede Kategori i databasen", "result" => 1)
        );

    } else {
        // set response code - 200 OK fordi Toft ikke kan håndtere et 404
        http_response_code(200);
    
        // Fortæl brugeren at der ikke kunne opdatere det valgte datasæt i databasen
        echo json_encode(
            array("message" => "Kunne ikke opdaterede Kategori i databasen",
            "result" => 0,
            "error" => '')
        );
    }