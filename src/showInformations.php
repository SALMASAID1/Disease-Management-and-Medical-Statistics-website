<?php
session_start(); 
include("../db/config.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients Data</title>
    <style>
        table {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        table, th, td {
            border: none;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #22266C;
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #f2f2f2;
            transition: background-color 0.3s ease;
        }

        td {
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        body {
           background: #c3cfe2;
            font-family: Arial, sans-serif;
        }

        h2 {
            color: #333;
            margin: 40px 0 20px 0;
            font-size: 24px;
        }

        .appointment-table {
            width: 40%;         /* Make table even smaller */
            max-width: 500px;   /* Reduce max width */
            margin: 20px auto;
        }

        .appointment-table th,
        .appointment-table td {
            text-align: center;  /* Center the text */
            padding: 12px;       /* Slightly reduce padding */
        }
    </style>
</head>
<body>

<center><h2>Patient's Information</h2></center>

<?php
if (isset($_SESSION['cin_from_home_page'])) {
    $cin = htmlspecialchars(trim($_SESSION['cin_from_home_page'])); // Sanitize input

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT p.*, 
                           GROUP_CONCAT(u.id) as appointment_ids, 
                           GROUP_CONCAT(u.status) as appointment_statuses,
                           GROUP_CONCAT(u.datee) as appointment_dates 
                           FROM patients p 
                           LEFT JOIN urgent_appointment u ON p.cin = u.fk_cin 
                           WHERE p.cin = ?
                           GROUP BY p.cin");
    $stmt->bind_param("s", $cin);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Display patient information table
        echo "<table>";
        echo "<tr><th>CIN</th><th>Full name</th><th>Age</th><th>Email</th><th>Phone</th><th>Blood type</th><th>Gender</th></tr>";
        echo "<tr>
                <td>" . htmlspecialchars($row["cin"]) . "</td>
                <td>" . htmlspecialchars($row["full_name"]) . "</td>
                <td>" . htmlspecialchars($row["age"]) . "</td>
                <td>" . htmlspecialchars($row["email"]) . "</td>
                <td>" . htmlspecialchars($row["phone"]) . "</td>
                <td>" . htmlspecialchars($row["blood_type"]) . "</td>
                <td>" . htmlspecialchars($row["sexe"]) . "</td>
              </tr>";
        echo "</table>";

        // Add spacing between tables
        echo "<br><br>";

        // Display all appointment statuses
        echo "<table class='appointment-table'>";
        echo "<tr><th>ID</th><th>Date</th><th>Urgent Appointment's Status</th></tr>";
        
        $appointment_ids = explode(',', $row['appointment_ids']);
        $appointment_dates = explode(',', $row['appointment_dates']);
        $appointment_statuses = explode(',', $row['appointment_statuses']);
        
        for ($i = 0; $i < count($appointment_ids); $i++) {
            echo "<tr>
                    <td>" . htmlspecialchars($appointment_ids[$i]) . "</td>
                    <td>" . htmlspecialchars($appointment_dates[$i]) . "</td>
                    <td>" . htmlspecialchars($appointment_statuses[$i]) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records found for CIN: " . htmlspecialchars($cin) . ".</p>";
    }
    
    $stmt->close();
} else {
    echo "<p>CIN parameter is missing from session.</p>";
}

// Close connection
$conn->close();
?>

</body>
</html>
