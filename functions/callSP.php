<?php


// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
include_once '../database.inc';

// Sætter lokale variabler til det der hentes fra GET requestet
// $sp er navnet på det stored procedure der skal kaldes
$sp = $_GET['sp'];
$param = $_GET['param'];
// Hvis der er flere parametre i GET requestet, delt ad via et ',' 'exploder' vi $param, dvs at hver gang den møder et ','
// deler den strengen og laver et array af selve strengen
$params = explode( ',', $param );

// Checker om brugeren har husket at give nogle parametre til stored proceduren
if(!isset($param)) {
    return 'You need to supply some Param';
} else {

    // Laver en forbindelse til databasen
    $database = new Database();
    $db = $database->getConnection();
    

    $sql = "";
    // Her laves nogle checks og håndtering af parametrene for at sikre os at de bliver formateret korrekt
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
    
    // Prøv at finde og køre proceduren på databasen
    try {
        // execute query
        $stmt->execute();
    } catch(Exception $e) {
        // Meld til brugeren hvis der ikke kunne finde den valgte stored procedure
        echo json_encode(
            array(
                "message" => "Stored procedure blev ikke fundet",
                "result" => "0",
                "e"=>$e->errorInfo
            )
        );
    }
    
    // Find antallet af rows med data som blev returneret
    $num = $stmt->rowCount();
    
    if($num>0){
    
        // Laver et nyt array af arrays for at store de returnerede datasæt
        $products_arr=array();
        $products_arr["records"]=array();
     try {
        // Så længe der findes data i datasættet, så gør vi denne funktion
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            $product_item=$row;
     
            // Tager dataet og pusher til det array som blev lavet til at store selve datasættet
            array_push($products_arr["records"], $product_item);
            $products_arr['result'] = 1;
        }
    
        // set response code - 200 OK
        http_response_code(200);
     
        // show sp returned dataset in json format
        echo json_encode($products_arr);
    } catch (Exception $e) {
        // Ellers vis at der ikke blev fundet noget data
        echo json_encode(
            array("message" => "Ingen Data blev returneret",
                "result" => "0")
        );
    }
    }else{
     
        // set response code - 200 OK, because Toft wants it to be OK even when error
        http_response_code(200);
     
        // tell the user no sp found
        echo json_encode(
            array("message" => "Ingen Data fundet",
            "result" => "0")
        );
    }
}