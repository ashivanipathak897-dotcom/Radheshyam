<?php
// Database Configuration
// Update these values with your actual database credentials
 $host = "localhost";
 $dbname = "construction_db";
 $username = "root"; // Default XAMPP/WAMP user. Change for live server.
 $password = "";     // Default XAMPP/WAMP password is empty. Change for live server.

// Set headers for JSON response
header("Content-Type: application/json; charset=UTF-8");

// Create Connection
 $conn = new mysqli($host, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and Validate Input
    $name = $conn->real_escape_string(trim($_POST['name']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $project_type = $conn->real_escape_string(trim($_POST['project_type']));
    $location = $conn->real_escape_string(trim($_POST['location']));
    $message = $conn->real_escape_string(trim($_POST['message']));

    // Prepare SQL Statement (Prevents SQL Injection)
    $stmt = $conn->prepare("INSERT INTO leads (name, phone, project_type, location, message) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("sssss", $name, $phone, $project_type, $location, $message);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Lead saved successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error saving data."]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Database query preparation failed."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

 $conn->close();
?>
