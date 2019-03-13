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

$database = new Database();
$db = $database->getConnection();

$Certificate = new Certificate($db);

// query products
$stmt = $Certificate->delete($id,$tblID);
if($stmt) {
    http_response_code(200);
    echo json_encode(
        array("message" => "Slettede element fra databasen", 
        "result" => 1
        )
    );

} else {
    // set response code - 404 Not found
    http_response_code(200);

    // tell the user no products found
    echo json_encode(
        array("message" => "Kunne ikke slette element fra databasen",
        "result" => 0,
        "error" => 'Elementet bliver brugt af en bruger i databasen')
    );
}

?>