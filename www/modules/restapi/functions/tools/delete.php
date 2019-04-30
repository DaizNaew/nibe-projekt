<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../../database.inc';
include_once '../../models/Equipment.php';

$id = $_GET['id'];

// Laver en connection til databasen og assigner forbindelsen til en lokal variabel
$database = new Database();
$db = $database->getConnection();

// Assigner $Equipment til at være Equipment model med selve database forbindelsen som parametre
$tool = new Equipment($db);

// Kører delete funktionen som er inde i Equipment modellen
$stmt = $tool->delete($id);
// Hvis databasen siger at der blev slettet noget, så er det $stmt true
if($stmt) {
    // set response code - 200 OK
    http_response_code(200);
    // Fortæl brugeren at der blev slettet det valgte datasæt
    echo json_encode(
        array("message" => "Slettede Kategori fra databasen", "result" => 1)
    );

} else {
    // set response code - 200 OK fordi Toft ikke kan håndtere et 404
    http_response_code(200);

    // Fortæl brugeren at der ikke kunne slette det valgte datasæt i databasen
    echo json_encode(
        array("message" => "Kunne ikke slette Kategori fra databasen",
        "result" => 0,
        "error" => '')
    );
}