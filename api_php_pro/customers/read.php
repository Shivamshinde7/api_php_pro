<?php

header('Access-Control_Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers, Authorization, X-Requested-With");

include('function.php');

$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method == "GET") {
    $customerList = getCustomerList();
    echo $customerList;
}else{
    $data = [
        'status' => '405',
        'message' => $request_method. 'Method_not_allowed',
    ];
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode($data);
}

?>