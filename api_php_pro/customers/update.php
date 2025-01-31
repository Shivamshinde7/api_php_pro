<?php

error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT'); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include('function.php');

$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method == "PUT") {
    // Get input data
    $inputData = json_decode(file_get_contents("php://input"), true);
    
    if (empty($inputData)) {
        $inputData = $_POST; // Handle form-data request
    }

    // Check if required fields are provided
    if (!isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
        echo json_encode(["status" => 422, "message" => "Missing required fields"]);
        exit;
    }

    // Call update function
    $response = updateCustomer($inputData);

    // Send response
    echo json_encode(["status" => 200, "message" => "Customer updated successfully", "data" => $response]);
} else {
    // Handle unsupported methods
    http_response_code(405);
    echo json_encode(["status" => 405, "message" => "$request_method Method Not Allowed"]);
}

?>
