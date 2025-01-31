<?php

header('Access-Control-Allow-Origin: *');  // Line 3: Corrected the header for CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers, Authorization, X-Requested-With");  // Line 5: Fixed header format

include('function.php');

$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method == "GET") {
    $customerList = getCustomerList(); 
    echo $customerList;
} else {
    $data = [
        'status' => '405',
        'message' => $request_method . ' Method Not Allowed',  
    ];
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode($data);
}

?>
