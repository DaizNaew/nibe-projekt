<?php


// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../../database.inc';
include_once '../../models/User.php';

$id = $_GET['id'];

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// query products
$stmt = $user->delete($id);

if($stmt === true) {

    http_response_code(200);
    echo json_encode(
        array("message" => "Sletning blev fuldført", "result" => 1)
    );

} else {
    // set response code - 404 Not found
    http_response_code(200);

    // tell the user no products found
    echo json_encode(
        array("message" => "Kunne ikke slette fra databasen",
        "result" => 0,
        "error" => $stmt->getMessage(),
        )
    );
}

?>