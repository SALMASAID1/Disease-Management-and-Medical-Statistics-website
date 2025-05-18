<?php
$servername = "localhost";
$username = "root"; 
$password = "Chicken@id1@@";
$database = "medicaldata";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Sanitize input
$cin = isset($_GET['cin']) ? mysqli_real_escape_string($conn, $_GET['cin']) : '';

// Get Dosage Data
$sql = "SELECT medicine, SUM(dosage * duration) as totaldosage FROM treatments WHERE cin_fk='$cin' GROUP BY medicine";
$result = $conn->query($sql);

$chartData = [['Medicine', 'Total Dosage']];
while ($row = $result->fetch_assoc()) {
    $chartData[] = [$row['medicine'], (float)$row['totaldosage']];
}

// Get Prescription Frequency Over Time
$sql_time = "SELECT medicine, DATE_FORMAT(visit_day, '%Y-%m') as month, COUNT(*) as count 
             FROM treatments WHERE cin_fk='$cin' 
             GROUP BY medicine, month 
             ORDER BY month";
$result_time = $conn->query($sql_time);

$chartDataTime = [['Month']];
$medicines = [];

while ($row = $result_time->fetch_assoc()) {
    if (!in_array($row['medicine'], $chartDataTime[0])) {
        $chartDataTime[0][] = $row['medicine'];
    }
    $medicines[$row['medicine']][$row['month']] = (int)$row['count'];
}

// Get Total Treatment Duration per Diagnosis
$sql_diag = "SELECT diagnostics, medicine,
SUM(CAST(SUBSTRING_INDEX(duration, ' ', 1) AS UNSIGNED)) AS total_duration
FROM treatments
where cin_fk='$cin' 
GROUP BY diagnostics,medicine";

$result_diag = $conn->query($sql_diag);

$chart_diagdata = [["Diagnosis", "Total Duration"]];
while ($row = $result_diag->fetch_assoc()) {
    $chart_diagdata[] = [$row['diagnostics'], (int)$row['total_duration']];
}


// Fill missing months
$allMonths = [];
foreach ($medicines as $data) {
    $allMonths = array_merge($allMonths, array_keys($data));
}
$allMonths = array_unique($allMonths);
sort($allMonths);

foreach ($allMonths as $month) {
    $chartDataRow = [$month];
    foreach ($chartDataTime[0] as $name) {
        if ($name != 'Month') {
            $chartDataRow[] = isset($medicines[$name][$month]) ? $medicines[$name][$month] : 0;
        }
    }
    $chartDataTime[] = $chartDataRow;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar', 'corechart']});
      google.charts.setOnLoadCallback(drawCharts);

      function drawCharts() {
        // Dosage Chart
        var dataDosage = google.visualization.arrayToDataTable(<?php echo json_encode($chartData); ?>);
        var optionsDosage = {
          chart: {
            title: 'Total Dosage per Medicine',
            subtitle: 'Based on patient treatment records',
          },
          bars: 'horizontal',
          height: 400,
          width: 800
        };
        var chartDosage = new google.charts.Bar(document.getElementById('barchart_material'));
        chartDosage.draw(dataDosage, google.charts.Bar.convertOptions(optionsDosage));

        // Prescription Over Time Chart
        var dataTime = google.visualization.arrayToDataTable(<?php echo json_encode($chartDataTime); ?>);
        var optionsTime = {
          title: 'Medicine Prescription Over Time',
          curveType: 'none',  // Remove smoothing to prevent dips
          legend: { position: 'bottom' },
          vAxis: { minValue: 0 }  // Ensure Y-axis doesn't go negative
        };
        var chartTime = new google.visualization.LineChart(document.getElementById('linechart_material'));
        chartTime.draw(dataTime, optionsTime);

        // 3D Pie Chart for Treatment Duration per Diagnosis
        var dataDiagnosis = google.visualization.arrayToDataTable(<?php echo json_encode($chart_diagdata); ?>);
        var optionsDiagnosis = {
          title: 'Total Treatment Duration per Diagnosis',
          is3D: true,  // Enables 3D effect
          height: 500,
          width: 800
        };
        var chartDiagnosis = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chartDiagnosis.draw(dataDiagnosis, optionsDiagnosis);
      }
    </script>
</head>

<body>
    <h2>Total Dosage per Medicine</h2>
    <div id="barchart_material" class="bar"></div>
    
    <br><br><br><br><br>
    
    <h2><center>Medicine Prescription Over Time</center></h2>
    <div id="linechart_material" class="line"></div>

    <br><br><br><br><br>

    <h2><center>Total Treatment Duration per Diagnosis</center></h2>
    <center><div id="piechart_3d" class="pie"></div></center>
</body>
</html>