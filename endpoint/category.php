<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../database.inc';
include_once '../models/Category.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    $database = new Database();
    $db = $database->getConnection();

    $Category = new Category($db);
    // query products
    $stmt = $Category->read($id);
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){

        $products_arr=array();
        $products_arr["categories"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            
            $product_item=array(
                "ID" => $ID,
                "katNavn" => $katNavn,
            );
    
            array_push($products_arr["categories"], $product_item);
        }
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show products data in json format
        echo json_encode($products_arr);
    }else{
    
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no products found
        echo json_encode(
            array("message" => "Ingen kategori fundet")
        );
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $database = new Database();
    $db = $database->getConnection();

    $Category = new Category($db);
    // query products
    $stmt = $Category->write($_POST);
    if($stmt) {

        http_response_code(200);
        echo json_encode(
            array("message" => "Indsatte Kategori i databasen", "result" => 1, "statement" => $stmt)
        );

    } else {
        // set response code - 404 Not found
        http_response_code(200);
    
        // tell the user no products found
        echo json_encode(
            array("message" => "Kunne ikke Kategori i databasen",
            "result" => 0,
            "error" => '')
        );
    }
}
?>