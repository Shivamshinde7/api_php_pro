<?php

require_once('../inc/dbcon.php');


function error422($message){
    $data = [
        'status' => 422,
        'message' => $message. ' Unprocessable Entity',
    ];
    header('HTTP/1.1 422 Unprocessable Entity');
    echo json_encode($data);
    exit();
}

function storeCustomer($customerInput) {
    global $conn;

    $name = mysqli_real_escape_string($conn, trim($customerInput['name']));
    $email = mysqli_real_escape_string($conn, trim($customerInput['email']));
    $phone = mysqli_real_escape_string($conn, trim($customerInput['phone']));

    if (empty($name)) {
        return error422('Name is required');
    } elseif (empty($email)) {
        return error422('Email is required');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return error422('Invalid email format');
    } elseif (empty($phone)) {
        return error422('Phone is required');
    }

    $query = "INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $phone);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $data = [
                'status' => 201,
                'message' => 'Customer Created Successfully',
            ];
            header('HTTP/1.1 201 Created');
            return json_encode($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error: ' . mysqli_error($conn),
            ];
            header('HTTP/1.1 500 Internal Server Error');
            return json_encode($data);
        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Error preparing the query: ' . mysqli_error($conn),
        ];
        header('HTTP/1.1 500 Internal Server Error');
        return json_encode($data);
    }
}


function getCustomerList(){

    global $conn;
    
    $query = "SELECT * FROM customers";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        
      if (mysqli_num_rows($query_run) > 0) {

        $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

        $data = [
            'status' => 200,
            'message' => 'Customers List Successfully',
            'data' => $res,
        ];
        header('HTTP/1.1 200 Ok');
        return json_encode($data);

      }else{
        $data = [
            'status' => 404,
            'message' => 'No Customers Found',
        ];
        header('HTTP/1.1 404 No Customers Found');
        return json_encode($data);
      }

    }else{
        $date = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header('HTTP/1.1 500 Internal Server Error');
        return json_encode($data);
    }
}

?>
