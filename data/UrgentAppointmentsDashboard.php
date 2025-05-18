<?php
header("Content-Type: application/json");

include("../db/config.php");

// Queries
$sql_pending = "SELECT  
                    urgent_appointment.id, 
                    patients.full_name, 
                    urgent_appointment.fk_cin, 
                    urgent_appointment.datee, 
                    urgent_appointment.message, 
                    urgent_appointment.status 
                FROM 
                    urgent_appointment , patients
                WHERE 
                    urgent_appointment.status = 'pending' and patients.cin = urgent_appointment.fk_cin ;";

$sql_accepted = "SELECT  
                    urgent_appointment.id, 
                    patients.full_name, 
                    urgent_appointment.fk_cin, 
                    urgent_appointment.datee, 
                    urgent_appointment.message, 
                    urgent_appointment.status 
                FROM 
                    urgent_appointment , patients
                WHERE 
                    urgent_appointment.status = 'accepted' and patients.cin = urgent_appointment.fk_cin ;";

$sql_rejected = "SELECT  
                    urgent_appointment.id, 
                    patients.full_name, 
                    urgent_appointment.fk_cin, 
                    urgent_appointment.datee, 
                    urgent_appointment.message, 
                    urgent_appointment.status 
                FROM 
                    urgent_appointment , patients
                WHERE 
                    urgent_appointment.status = 'rejected'and patients.cin = urgent_appointment.fk_cin ;";

// Initialize response array
$response = [
    "pending" => [],
    "accepted" => [],
    "rejected" => []
];

// Fetch pending requests
$result_pending = $conn->query($sql_pending);
if ($result_pending) {
    while ($row = $result_pending->fetch_assoc()) {
        $response["pending"][] = [
            'id' => (int)$row['id'],  // Cast to integer
            'patientName' => $row['full_name'],
            'cin' => $row['fk_cin'],
            'visitDate' => $row['datee'],
            'message' => $row['message'],
            'status' => $row['status']
        ];
    }
} else {
    $response["error"] = "Failed to fetch pending requests: " . $conn->error;
}

// Fetch accepted requests
$result_accepted = $conn->query($sql_accepted);
if ($result_accepted) {
    while ($row = $result_accepted->fetch_assoc()) {
        $response["accepted"][] = [
            'id' => (int)$row['id'],
            'patientName' => $row['full_name'],
            'cin' => $row['fk_cin'],
            'visitDate' => $row['datee'],
            'message' => $row['message'],
            'status' => $row['status']
        ];
    }
} else {
    $response["error"] = "Failed to fetch accepted requests: " . $conn->error;
}

// Fetch rejected requests
$result_rejected = $conn->query($sql_rejected);
if ($result_rejected) {
    while ($row = $result_rejected->fetch_assoc()) {
        $response["rejected"][] = [
            'id' => (int)$row['id'],
            'patientName' => $row['full_name'],
            'cin' => $row['fk_cin'],
            'visitDate' => $row['datee'],
            'message' => $row['message'],
            'status' => $row['status']
        ];
    }
} else {
    $response["error"] = "Failed to fetch rejected requests: " . $conn->error;
}

// Output the response as JSON
echo json_encode($response);

$conn->close();
?>