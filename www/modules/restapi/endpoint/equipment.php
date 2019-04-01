<?php

// required headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 1000');
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../database.inc';
include_once '../models/Equipment.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if(empty($_GET['limit'])){
        $limit = 20000;
    }else{
        $limit = $_GET['limit'];
    }

    if(empty($_GET['offset'])){
        $offset = 0;
    }else{
        $offset = $_GET['offset'];
    }

    $id = $_GET['id'];

    $database = new Database();
    $db = $database->getConnection();

    $equipment = new Equipment($db);
    // query products
    $stmt = $equipment->read($id, $limit, $offset);
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){

        $products_arr=array();
        $products_arr["records"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            
            $product_item=array(
                "ID" => $ID,
                "activeName" => $aktivNavn,
                "brandName" => $brand,
                "modelName" => $model,
                "serialNumber" => $serieNummer,
                "assetTag" => $assetTag,
                "condition" => $stand,
                "categoryID" => $katID,
                "categoryName" => $katNavn,
                "inHouse" => $inhouse,
                "reserved" => $reserved,
            );
    
            array_push($products_arr["records"], $product_item);
        }
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show products data in json format
        echo json_encode($products_arr);
    }else{
    
        // set response code - 404 Not found
        http_response_code(200);
    
        // tell the user no products found
        echo json_encode(
            array("message" => "Ingen værktøj fundet")
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
            array("message" => "Insert blev fuldført", "result" => 1)
        );

    } else {
        // set response code - 404 Not found
        http_response_code(200);
    
        // tell the user no products found
        echo json_encode(
            array("message" => "Insert blev ikke fuldført",
            "result" => 0,
            "error" => '')
        );
    }
}
?>