<?php
header('Content-Type: application/json');
include('../db/config.php');

$sql = "SELECT 
    appointment_date, COUNT(*) AS Numbers
FROM 
    appointment_date
WHERE 
    appointment_date BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 6 DAY)
GROUP BY 
    appointment_date
ORDER BY 
    appointment_date";

$result = $conn->query($sql);

$data = [];
$data[] = ['Appointment Date', 'Numbers']; // Header row for Google Charts

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [$row['appointment_date'], (int)$row['Numbers']];
    }
}

echo json_encode($data);
$conn->close();
?>