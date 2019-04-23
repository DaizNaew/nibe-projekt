<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // database connection will be here
    include_once '../../database.inc';
    include_once '../../models/User.php';

    $id = $_GET['id'];

    // Laver en connection til databasen og assigner forbindelsen til en lokal variabel
    $database = new Database();
    $db = $database->getConnection();

    // Assigner $User til at være User model med selve database forbindelsen som parametre
    $User = new User($db);

    // Kører getByCard funktionen som er inde i User modellen
    $stmt = $User->getByCard($id);

    // Find antallet af rows med data som blev returneret
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){

        // Laver et nyt array af arrays for at store de returnerede datasæt
        $users_arr=array();
        $users_arr["records"]=array();
    
        // Så længe der findes data i datasættet, så gør vi denne funktion
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // Derefter udpakkes $row så alle de variabler det indeholder nu er at findes som lokale variabler, så bliver arrayet bare populated med data
            extract($row);
            $users_item=array(
                "ID" => $ID,
                "name" => $name,
                "phoneNumber" => $phoneNumber,
                "address" => $address,
                "cardID" => $cardID,
                "usergruppe" => $usergruppe,
                "usergruppetitle" => $title,
            );
    
            // Push til arrayet så det kan sendes til brugeren
            array_push($users_arr["records"], $users_item);
        }
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show users data in json format
        echo json_encode($users_arr);
    }else{
    
        // set response code - 404 Not found
        http_response_code(404);
    
        // Fortæl brugeren at der ikke blev fundet nogle brugere med det medsendte kort ID
        echo json_encode(
            array("message" => "Ingen bruger fundet", "result" => 0)
        );
    }
    