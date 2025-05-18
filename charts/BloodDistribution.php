<?php

use BcMath\Number;

header('Content-typ:application/json');
include("../db/config.php");

$sql = "SELECT blood_type , count(*) as Number FROM patients group by Blood_type order by Blood_type";
$result =  $conn->query($sql);

$data = array();
$data[] = ['blood type' , 'Number'];

while($row = $result->fetch_assoc()){
    $data[] = [$row['blood_type'] , (int)$row['Number']];
}

echo json_encode($data);

$conn -> close();

?>