<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../database.inc';
include_once '../models/Category.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    $database = new Database();
    $db = $database->getConnection();

    $equipment = new Equipment($db);
    // query products
    $stmt = $equipment->read($id);
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

    $equipment = new Equipment($db);
    // query products
    $stmt = $equipment->write($_POST);
    if($stmt) {

        http_response_code(200);
        echo json_encode(
            array("message" => "Indsatte Kategori i databasen", "result" => 1)
        );

    } else {
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no products found
        echo json_encode(
            array("message" => "Kunne ikke Kategori i databasen",
            "result" => 0,
            "error" => '')
        );
    }
}
?>