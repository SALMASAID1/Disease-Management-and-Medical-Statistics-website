<?php
header('Content-Type:application/json');
include("../db/config.php");

$sql = "SELECT 
    diagnostics , count(*) as Numbers
from
     treatments
where
      visit_day between subdate(curdate() , interval 7 day )  and current_date()
group by
      diagnostics ";

$result = $conn ->query($sql);
$data = array();
$data[] = ['diagnostics' , ''];

if($result && $result -> fetch_assoc() > 0  )
{
    while($row = $result -> fetch_assoc()){
        $data[] = [$row['diagnostics'] , $row['Numbers']];
    }
}

echo json_encode($data);

$conn -> close();

?>