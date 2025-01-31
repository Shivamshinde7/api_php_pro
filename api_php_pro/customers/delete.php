<?php

include('function.php');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $customerInput);
    
    if (!isset($customerInput['id'])) {
        error422('ID is required');
    }

    $id = mysqli_real_escape_string($conn, trim($customerInput['id']));
    $response = deleteCustomer($id);
    echo $response;
} else {
    echo json_encode(["status" => 405, "message" => "Method Not Allowed"]);
}

?>
