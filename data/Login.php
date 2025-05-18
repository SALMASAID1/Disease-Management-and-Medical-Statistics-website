<?php
session_start();
include("../db/config.php");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize GET parameters
$cin = isset($_POST['cin']) ? trim($_POST['cin']) : null;
$role = isset($_POST['role']) ? trim($_POST['role']) : null;

$_SESSION['cin_from_home_page'] = $cin;
$_SESSION['role_from_home_page'] = $role;

if ($cin && $role) {
    // echo "alert('your cin is $cin')";
    // echo "alert('your role is $role')";
    // Check if the role is "receiver"
    if ($role === "receiver") {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM patients WHERE cin = ?");
        $stmt->bind_param("s", $cin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Redirect to patientApp.html if a match is found
            header("Location: ../src/patientApp.php");
            exit();
        } else {
            echo "<script>alert('Invalid CIN. Please check your input.'); window.location.href = 'medicalApp.html';</script>";
            exit();
        }
        $stmt->close();
    }
    if ($role === "provider") {
        $stmt = $conn->prepare("SELECT * FROM doctors WHERE cin_doctor = ?");
        $stmt->bind_param("s", $cin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0 ) {
            $row = $result->fetch_assoc();
            $DoctorName = $row['full_name'];
            header("Location: ../src/doctorsPage.php?Doctor={$DoctorName}");
            exit();
        } else {
            echo "<script>alert('Invalid CIN. Please check your input.'); window.location.href = 'medicalApp.html';</script>";
            exit();
        }

        $stmt->close();
    }
} else {
    echo "<script>alert('CIN and Role are required.'); window.location.href = 'medicalApp.html';</script>";
    exit();
}

// Close connection
$conn->close();
