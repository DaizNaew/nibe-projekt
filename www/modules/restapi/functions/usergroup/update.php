<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // database connection will be here
    include_once '../../database.inc';
    include_once '../../models/Usergroup.php';

    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();

    // Assigner $Usergroup til at være Usergroup model med selve database forbindelsen som parametre
    $Usergroup = new Usergroup($db);

    // Kører update funktionen som er inde i Usergroup modellen
    $stmt = $Usergroup->update($_POST);

    // Hvis databasen siger at der blev opdateret, så er det $stmt true
    if($stmt) {

        // Fortæl brugeren at det valgte datasæt blev opdateret i databasen
        http_response_code(200);
        echo json_encode(
            array("message" => "Opdaterede Kategori i databasen", "result" => 1)
        );

    } else {
       // set response code - 200 OK fordi Toft ikke kan håndtere et 404
        http_response_code(200);
    
        // Fortæl brugeren at der ikke kunne opdateres det valgte datasæt i databasen
        echo json_encode(
            array("message" => "Kunne ikke opdaterede Kategori i databasen",
            "result" => 0,
            "error" => '')
        );
    }