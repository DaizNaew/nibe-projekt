<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../../database.inc';
include_once '../../models/Certificate.php';

$id = $_GET['id'];
$tblID = $_GET['tableid'];
$table_name = ["certifikat","kørekort"];

// Laver en connection til databasen og assigner forbindelsen til en lokal variabel
$database = new Database();
$db = $database->getConnection();

// Assigner $Certificate til at være Certificate model med selve database forbindelsen som parametre
$Certificate = new Certificate($db);

// Kører delete funktionen som er inde i Certificate modellen
$stmt = $Certificate->delete($id,$tblID);
// Hvis databasen siger at der blev slettet noget, så er det $stmt true
if($stmt) {
    // set response code - 200 OK
    http_response_code(200);
    // Fortæl brugeren at der blev slettet det valgte datasæt
    echo json_encode(
        array("message" => "Slettede element fra databasen", 
        "result" => 1
        )
    );

} else {
    // set response code - 200 OK fordi Toft ikke kan håndtere et 404
    http_response_code(200);

    // Fortæl brugeren at der ikke kunne slette det valgte datasæt i databasen
    echo json_encode(
        array("message" => "Kunne ikke slette element fra databasen",
        "result" => 0,
        "error" => 'Elementet bliver brugt af en bruger i databasen')
    );
}