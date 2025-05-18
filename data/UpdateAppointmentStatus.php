<?php
header("Content-type: application/json");

include("../db/config.php");

// Get the data from the POST request
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['id']) || !isset($input['status'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$id = $input['id'];
$status = $input['status'];

// Update the database
$sql = "UPDATE urgent_appointment SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Failed to update status"]);
}
// if ($status == 'rejected'){
//     $SQL_delet = "DELETE FROM urgent_appointment 
//             WHERE id = ? and status = 'rejected'; 
//     ";
//     $stmt_delete = $conn -> prepare($SQL_delet);
//     $stmt_delete -> bind_param("i" , $input['id']);
//     $result =  $stmt_delete -> execute();
// }

$stmt->close();
$conn->close();
?>