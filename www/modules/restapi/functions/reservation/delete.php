<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../../database.inc';
include_once '../../models/Reservation.php';

$id = $_GET['id'];
$equipmentID = $_GET['equipmentID'];

// Laver en connection til databasen og assigner forbindelsen til en lokal variabel
$database = new Database();
$db = $database->getConnection();

// Assigner $Reservation til at være Reservation model med selve database forbindelsen som parametre
$Reservation = new Reservation($db);

// Kører delete funktionen som er inde i Reservation modellen
$stmt = $Reservation->delete($id, $equipmentID);
// Hvis databasen siger at der blev slettet noget, så er det $stmt true
if($stmt) {
    // set response code - 200 OK
    http_response_code(200);
    // Fortæl brugeren at der blev slettet det valgte datasæt
    echo json_encode(
        array("message" => "Slettede Reservation fra databasen", 
        "result" => 1
        )
    );

} else {
    // set response code - 200 OK fordi Toft ikke kan håndtere et 404
    http_response_code(200);

    // Fortæl brugeren at der ikke kunne slette det valgte datasæt i databasen
    echo json_encode(
        array("message" => "Kunne ikke slette Reservation fra databasen",
        "result" => 0,
        "error" => 'Reservation bliver brugt af noget i databasen')
    );
}