<?php
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$pass = "Chicken@id1@@";
$dbname = "medicaldata"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT * FROM patients";

$result = $conn->query($sql);

$patients = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
}

$conn->close();

echo json_encode($patients);



