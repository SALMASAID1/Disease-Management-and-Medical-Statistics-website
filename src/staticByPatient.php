<?php
session_start(); // Add session_start() at the beginning
include("../db/config.php");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize input to prevent SQL injection
$cin = isset($_SESSION['cin_from_home_page']) ? mysqli_real_escape_string($conn, $_SESSION['cin_from_home_page']) : '';

// Query to get total dosage per medicine
$sql = "SELECT medicine, SUM(dosage * duration) as totaldosage FROM treatments WHERE cin_fk='$cin' GROUP BY medicine";
$result = $conn->query($sql);

$chartData = [['Medicine', 'Total Dosage']];
while ($row = $result->fetch_assoc()) {
    $chartData[] = [$row['medicine'], (float)$row['totaldosage']];
}

// Query to get prescription frequency over time
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

// Query to get total treatment duration per diagnosis
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

//// Query to get total duration fct dosage 
$sql_dos= "SELECT 
    medicine, 
    SUM(CAST(SUBSTRING_INDEX(TRIM(duration), ' ', 1) AS UNSIGNED)) AS toduration,  
    SUM(CAST(TRIM(dosage) AS UNSIGNED)) AS Tdosage
FROM treatments
WHERE cin_fk = '$cin'
GROUP BY medicine
ORDER BY toduration";
$result_dos= $conn->query($sql_dos);

$chart_dosdata = [["Medicine", "Dosage", "Duration"]];  // Fixed column headers
while ($row = $result_dos->fetch_assoc()) {
    $chart_dosdata[] = [$row['medicine'], (int)$row['Tdosage'], (int)$row['toduration']];
}

// Fill missing months to maintain chart continuity
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
    <link rel="stylesheet" href="../css/staticByPatient.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar', 'corechart']});
      google.charts.setOnLoadCallback(drawCharts);

      function drawCharts() {
        // Chart for total dosage per medicine
        var dataDosage = google.visualization.arrayToDataTable(<?php echo json_encode($chartData); ?>);
        var optionsDosage = {
          chart: {
            title: ' ',
            subtitle: ' ',
          },
          bars: 'horizontal',
          height: 400,
          width: 1100
        };
        var chartDosage = new google.charts.Bar(document.getElementById('barchart_material'));
        chartDosage.draw(dataDosage, google.charts.Bar.convertOptions(optionsDosage));

        // Chart for medicine prescription over time
        var dataTime = google.visualization.arrayToDataTable(<?php echo json_encode($chartDataTime); ?>);
        var optionsTime = {
          title: ' ',
          curveType: 'none',  // Prevent dips in the graph
          legend: { position: 'bottom' },
          vAxis: { minValue: 0 }
        };
        var chartTime = new google.visualization.LineChart(document.getElementById('linechart_material'));
        chartTime.draw(dataTime, optionsTime);

        // Pie chart for total treatment duration per diagnosis
        var dataDiagnosis = google.visualization.arrayToDataTable(<?php echo json_encode($chart_diagdata); ?>);
        var optionsDiagnosis = {
          title: ' ',
          is3D: true,
          height: 400,
          width: 1100
        };
        var chartDiagnosis = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chartDiagnosis.draw(dataDiagnosis, optionsDiagnosis);

        // Line chart for dosage and duration relationship
        var dataDosageDuration = google.visualization.arrayToDataTable(<?php echo json_encode($chart_dosdata); ?>);
        var optionsDosageDuration = {
            title: ' ',
            curveType: 'function',
            legend: { position: 'bottom' },
            hAxis: { title: 'Medicine' },
            vAxis: { title: 'Value' },
            series: {
                0: { targetAxisIndex: 0 },
                1: { targetAxisIndex: 1 }
            },
            vAxes: {
                0: { title: 'Dosage' },
                1: { title: 'Duration' }
            }
        };
        var chartDosageDuration = new google.visualization.LineChart(document.getElementById('dosage_duration_chart'));
        chartDosageDuration.draw(dataDosageDuration, optionsDosageDuration);
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

    <br><br><br><br><br>

    <h2><center>Medicine Dosage and Duration Relationship</center></h2>
    <div id="dosage_duration_chart" class="style"></div>
</body>
</html>
