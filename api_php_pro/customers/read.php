<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers, Authorization, X-Requested-With");

include('function.php');

$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method == "GET") {
    // Call the function that retrieves customer data from your database
    $customerList = getCustomerList();  // This should return a JSON encoded string
    echo $customerList;
} else {
    // Return an error if the request method is not GET
    $data = [
        'status' => '405',
        'message' => $request_method . ' Method Not Allowed',
    ];
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode($data);
}

?>
