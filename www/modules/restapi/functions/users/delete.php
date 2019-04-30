<?php


// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../../database.inc';
include_once '../../models/User.php';

$id = $_GET['id'];

// Laver en connection til databasen og assigner forbindelsen til en lokal variabel
$database = new Database();
$db = $database->getConnection();

// Assigner $User til at være User model med selve database forbindelsen som parametre
$user = new User($db);

// Kører delete funktionen som er inde i User modellen
$stmt = $user->delete($id);

// Hvis databasen siger at der blev slettet noget, så er det $stmt true
if($stmt === true) {

    // set response code - 200 OK
    http_response_code(200);
    // Fortæl brugeren at der blev slettet det valgte datasæt
    echo json_encode(
        array("message" => "Sletning blev fuldført", "result" => 1)
    );

} else {
    // set response code - 200 OK fordi Toft ikke kan håndtere et 404
    http_response_code(200);

    // Fortæl brugeren at der ikke kunne slette det valgte datasæt i databasen, og send error beskeden med så vi kan tjekke den
    echo json_encode(
        array("message" => "Kunne ikke slette fra databasen",
        "result" => 0,
        "error" => $stmt->getMessage(),
        )
    );
}