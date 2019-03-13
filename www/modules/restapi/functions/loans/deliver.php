<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // database connection will be here
    include_once '../../database.inc';
    include_once '../../models/Loaned.php';

    $database = new Database();
    $db = $database->getConnection();

    $Loaned = new Loaned($db);

    $stmt = $Loaned->deliver($_POST);

    if($stmt === true) {

        http_response_code(200);
        echo json_encode(
            array("message" => "Afleverede loan i databasen", "result" => 1)
        );

    } else {
        // set response code - 404 Not found
        http_response_code(200);
    
        // tell the user no products found
        echo json_encode(
            array("message" => "Kunne ikke Aflevere loan i databasen",
            "result" => 0,
            "error" => $stmt->getMessage())
        );
    }