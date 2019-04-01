<?php


// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../database.inc';

$sp = $_GET['sp'];
$param = $_GET['param'];
$params = explode( ',', $param );

if(!isset($param)) {
    return 'You need to supply some Param';
} else {

    $database = new Database();
    $db = $database->getConnection();
    
    // constructor with $db as database connection
    $sql = "";
    if(sizeof($params) < 2) {
        if(!is_int($param)) {
            $sql = 'CALL `'.$sp.'`("'.$param.'")';
        } else {
            $sql = 'CALL `'.$sp.'`('.$param.')';
        }
    } else {

        $sql = "CALL `$sp`($param)";
    }
    
    
    
    // prepare query statement
    $stmt = $db->prepare($sql);
        
    // execute query
    $stmt->execute();
    
    $num = $stmt->rowCount();
    
    if($num>0){
    
        $products_arr=array();
        $products_arr["records"]=array();
     try {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            $product_item=$row;
     
            array_push($products_arr["records"], $product_item);
        }
    
        // set response code - 200 OK
        http_response_code(200);
     
        // show sp data in json format
        echo json_encode($products_arr);
    } catch (Exception $e) {
        echo json_encode(
            array("message" => "Ingen Data blev returneret")
        );
    }
    }else{
     
        // set response code - 404 Not found
        http_response_code(404);
     
        // tell the user no sp found
        echo json_encode(
            array("message" => "Ingen Data fundet")
        );
    }
}


?>