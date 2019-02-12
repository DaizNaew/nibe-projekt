<?php


// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../../database.inc';
include_once '../../models/Loaned.php';

$id = $_GET['id'];

$database = new Database();
$db = $database->getConnection();

$Loaned = new Loaned($db);

// query products
$stmt = $Loaned->delete($id);
if($stmt) {
    http_response_code(200);
    echo json_encode(
        array("message" => "Slettede Loan fra databasen", 
        "result" => 1
        )
    );

} else {
    // set response code - 404 Not found
    http_response_code(200);

    // tell the user no products found
    echo json_encode(
        array("message" => "Kunne ikke slette Loan fra databasen",
        "result" => 0,
        "error" => 'Loan bliver brugt af værktøjer i databasen')
    );
}

?>