<?php
session_start(); // Add session_start() at the beginning
include("../db/config.php");
try {
    // Get and validate inputs
    $date = $_POST['date'] ?? '';
    $message = $_POST['message'] ?? '';
    $cin = $_SESSION['cin_from_home_page'] ?? null; // Get CIN from session

    // Validate date
    $inputDate = strtotime($date);
    $today = strtotime(date('Y-m-d')); // Get today's date without time

    if (!$inputDate) {
        echo "<script>
            alert('Invalid date format');
            window.location.href = 'patientApp.php';
        </script>";
        exit();
    }
    
    if ($inputDate < $today) {
        echo "<script>
            alert('Appointment date must be in the future');
            window.location.href = 'patientApp.php';
        </script>";
        exit();
    }

    // Validate CIN
    if (!$cin) {
        echo "<script>
            alert('CIN is required');
            window.location.href = 'patientApp.php';
        </script>";
        exit();
    }

    // Validate message
    if (empty($message) || strlen($message) > 1000) {
        echo "<script>
            alert('Invalid message content');
            window.location.href = 'patientApp.php';
        </script>";
        exit();
    }

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO urgent_appointment (fk_cin, datee, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $cin, $date, $message);

    if ($stmt->execute()) {
        echo "<script>
            alert('Appointment request submitted successfully');
            window.location.href = 'patientApp.php';
        </script>";
    } else {
        echo "<script>
            alert('Failed to submit appointment');
            window.location.href = 'patientApp.php';
        </script>";
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
