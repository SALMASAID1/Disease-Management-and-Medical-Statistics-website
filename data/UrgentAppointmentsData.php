<?php
header("Content-type: application/json");

include("../db/config.php");


// $CIN = $_SESSION['CIN'];

// $stmt = $conn ->prepare($sql);
// $stmt -> bind_param('s' , $CIN);
// $stmt -> execute();
// $result = $stmt -> get_result();

$sql = "SELECT  
            id , full_name , fk_cin,datee,message , status 
        FROM 
            urgent_appointment , patients
        where 
            cin= fk_cin and status = 'pending';";
$result = $conn ->query($sql);

if (!$result) {
    echo json_encode(["error" => "Database query failed: " . $conn->error]);
    $conn->close();
    exit;
}

$data = [];
while($row = $result->fetch_assoc()){
    $data[] = [
        'id' => $row['id'],
        'full_name' => $row['full_name'],
        'CIN' => $row['fk_cin'],
        'date' =>$row['datee'],
        'message' => $row['message'],
        'status' => $row['status']
    ];
}

echo json_encode($data);
$conn -> close();

?>