<?php


// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../database.inc';
include_once '../models/User.php';

$uname = $_GET['uname'];
$pword = $_GET['pword'];

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// query products
$stmt = $user->checkPass($pword,$uname);
if($stmt) {
    http_response_code(200);
    print_r (json_encode($stmt)); 
    return;
}

?>