<?php 

require_once('../inc/dbcon.php');

function error422($message){
    $data = [
        'status' => 422,
        'message' => $message . ' Unprocessable Entity',
    ];
    header('HTTP/1.1 422 Unprocessable Entity');
    echo json_encode($data);
    exit();
}

// Create a new customer
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
            return json_encode(["status" => 500, "message" => "Internal Server Error: " . mysqli_error($conn)]);
        }
    } else {
        return json_encode(["status" => 500, "message" => "Error preparing query: " . mysqli_error($conn)]);
    }
}

// Get all customers
function getCustomerList(){
    global $conn;
    
    $query = "SELECT * FROM customers";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            return json_encode(["status" => 200, "message" => "Customers List Retrieved", "data" => $res]);
        } else {
            return json_encode(["status" => 404, "message" => "No Customers Found"]);
        }
    } else {
        return json_encode(["status" => 500, "message" => "Internal Server Error"]);
    }
}

// Get a single customer by ID
function getCustomer($customerInput) {
    global $conn;

    $id = mysqli_real_escape_string($conn, trim($customerInput['id']));

    if (empty($id)) {
        return error422('ID is required');
    }

    $query = "SELECT * FROM customers WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $res = mysqli_stmt_get_result($stmt);
            $customer = mysqli_fetch_assoc($res);

            if ($customer) {
                return json_encode(["status" => 200, "message" => "Customer Found", "data" => $customer]);
            } else {
                return json_encode(["status" => 404, "message" => "Customer Not Found"]);
            }
        } else {
            return json_encode(["status" => 500, "message" => "Internal Server Error: " . mysqli_error($conn)]);
        }
    } else {
        return json_encode(["status" => 500, "message" => "Error preparing query: " . mysqli_error($conn)]);
    }
}

// Update an existing customer
function updateCustomer($customerInput) {
    global $conn;

    if (!isset($customerInput['id'], $customerInput['name'], $customerInput['email'], $customerInput['phone'])) {
        return error422('ID, Name, Email, and Phone are required');
    }

    $id = mysqli_real_escape_string($conn, trim($customerInput['id']));
    $name = mysqli_real_escape_string($conn, trim($customerInput['name']));
    $email = mysqli_real_escape_string($conn, trim($customerInput['email']));
    $phone = mysqli_real_escape_string($conn, trim($customerInput['phone']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return error422('Invalid email format');
    }

    $query = "UPDATE customers SET name=?, email=?, phone=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssi', $name, $email, $phone, $id);
        $result = mysqli_stmt_execute($stmt);

        if ($result && mysqli_stmt_affected_rows($stmt) > 0) {
            return json_encode(["status" => 200, "message" => "Customer Updated Successfully"]);
        } else {
            return json_encode(["status" => 404, "message" => "Customer Not Found or No Change"]);
        }
    } else {
        return json_encode(["status" => 500, "message" => "Error preparing query: " . mysqli_error($conn)]);
    }
}

// Delete a customer
function deleteCustomer($customerInput) {
    global $conn;

    if (!isset($customerInput['id'])) {
        return error422('ID is required');
    }

    $id = mysqli_real_escape_string($conn, trim($customerInput['id']));

    $query = "DELETE FROM customers WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $result = mysqli_stmt_execute($stmt);

        if ($result && mysqli_stmt_affected_rows($stmt) > 0) {
            return json_encode(["status" => 200, "message" => "Customer Deleted Successfully"]);
        } else {
            return json_encode(["status" => 404, "message" => "Customer Not Found"]);
        }
    } else {
        return json_encode(["status" => 500, "message" => "Error preparing query: " . mysqli_error($conn)]);
    }
}

?>
