<?php
header('Content-Type: application/json');
include('../db/config.php');

$sql = "SELECT age , count(*) as Number FROM patients group by age order by Number ";
$result = $conn->query($sql);


$data = array();
$data[] = ['Age', 'Number']; // Column names for Google Charts

while ($row = $result->fetch_assoc()) {
    $data[] = [$row['age'], (int)$row['Number']];
}
echo json_encode($data);
$conn->close();
?>