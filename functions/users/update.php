<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // database connection will be here
    include_once '../../database.inc';
    include_once '../../models/User.php';

    $database = new Database();
    $db = $database->getConnection();

    $User = new User($db);

    $stmt = $User->update($_POST);

    if($stmt === true) {

        http_response_code(200);
        echo json_encode(
            array("message" => "Opdatering blev fuldført", "result" => 1)
        );

    } else {
        // set response code - 404 Not found
        http_response_code(200);
    
        // tell the user no products found
        echo json_encode(
            array("message" => "Kunne ikke Opdatere databasen",
            "result" => 0,
            "error" => $stmt->getMessage(),
            )
        );
    }