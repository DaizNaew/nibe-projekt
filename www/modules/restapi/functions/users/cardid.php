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

    $User = new User($db);

    $stmt = $User->getByCard($id);

    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){

        $products_arr=array();
        $products_arr["records"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            
            $product_item=array(
                "ID" => $ID,
                "name" => $name,
                "phoneNumber" => $phoneNumber,
                "address" => $address,
                "cardID" => $cardID,
                "usergruppe" => $usergruppe,
                "usergruppetitle" => $title,
            );
    
            array_push($products_arr["records"], $product_item);
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
            array("message" => "Ingen bruger fundet", "result" => 0)
        );
    }
    