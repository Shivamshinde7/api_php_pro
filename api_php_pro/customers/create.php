<?php

error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS'); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include('function.php');

$request_method = $_SERVER["REQUEST_METHOD"];

if($request_method == "POST"){

    $inputData = json_decode(file_get_contents("php://input"), true);
    if (empty($inputData)) {
        $inputData = $_POST;
    }
    
    if (!isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
        echo json_encode(["status" => 422, "message" => "Missing required fields"]);
        exit;
    }
    
    $storeCustomer = storeCustomer($inputData);
    echo $storeCustomer;
    

} elseif ($request_method == "OPTIONS") {
    header('HTTP/1.1 200 OK');
    exit;

} else {
    $data = [
        'status' => '405',
        'message' => $request_method . ' Method Not Allowed',
    ];
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode($data);
}

?>
