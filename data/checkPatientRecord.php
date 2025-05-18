<?php
session_start();
include("../db/config.php");

try {
    $CIN = $_POST['CIN'];
    $VIST_date = isset($_POST['visitDay']) ? $_POST['visitDay'] : null;

    if (isset($CIN)) {
        if (!empty($VIST_date)) {
            $sql = "SELECT * FROM treatments, patients WHERE cin_fk = cin and cin_fk = ? AND visit_day = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $CIN, $VIST_date);
        } else {
            $sql = "SELECT * FROM treatments, patients WHERE cin_fk = cin and cin_fk = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $CIN);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Clear any existing patient data in session
            unset($_SESSION['current_patient']);
            unset($_SESSION['patient_treatments']);
            
            // Prepare data arrays
            $data = [];
            $data[] = ['CIN','full_name', 'email', 'phone', 'age', 'blood_type', 'sexe',
                      'medicine', 'dosage', 'duration', 'visit_day', 'diagnostics', 'symptoms'];
            
            // Store patient info in session
            $patientInfo = null;
            $_SESSION['patient_treatments'] = [];
            
            while ($row = $result->fetch_assoc()) {
                // If this is the first row, extract patient info
                if ($patientInfo === null) {
                    $patientInfo = [
                        'cin' => $row['cin'],
                        'full_name' => $row['full_name'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'age' => $row['age'],
                        'blood_type' => $row['blood_type'],
                        'sexe' => $row['sexe']
                    ];
                    
                    // Store in session
                    $_SESSION['current_patient'] = $patientInfo;
                    
                    // Also store search parameters
                    $_SESSION['last_search'] = [
                        'cin' => $CIN,
                        'visit_day' => $VIST_date
                    ];
                }
                // Add to data array
                $data[] = [
                    $row['cin'],           
                    $row['full_name'],     
                    $row['email'],         
                    $row['phone'],         
                    $row['age'],           
                    $row['blood_type'],    
                    $row['sexe'],          
                    $row['medicine'],      
                    $row['dosage'],        
                    $row['duration'],      
                    $row['visit_day'],     
                    $row['diagnostics'],   
                    $row['symptomes'] 
                ];
                
                // Store treatment in session
                $_SESSION['patient_treatments'][] = [
                    'medicine' => $row['medicine'],
                    'dosage' => $row['dosage'],
                    'duration' => $row['duration'],
                    'visit_day' => $row['visit_day'],
                    'diagnostics' => $row['diagnostics'],
                    'symptoms' => $row['symptomes']
                ];
            }
            
            // Store full data array in session
            $_SESSION['patient_data'] = $data;
            
            // Track this access in session history
            if (!isset($_SESSION['access_history'])) {
                $_SESSION['access_history'] = [];
            }
            
            // Add to access history with timestamp
            $_SESSION['access_history'][] = [
                'cin' => $CIN,
                'visit_day' => $VIST_date,
                'timestamp' => date('Y-m-d H:i:s'),
                'record_count' => $result->num_rows
            ];
            
            // Limit history to last 10 accesses
            if (count($_SESSION['access_history']) > 10) {
                $_SESSION['access_history'] = array_slice($_SESSION['access_history'], -10);
            }
            
            // Set a success flag in session
            $_SESSION['data_loaded'] = true;
            
            // Redirect to Records.php
            header("Location: ../src/Records.php");
            exit;
            
        } else {
            // No records found
            $_SESSION['error_message'] = 'No treatments found for this patient';
            
            // Clear current patient session data
            unset($_SESSION['current_patient']);
            unset($_SESSION['patient_treatments']);
            unset($_SESSION['patient_data']);
            
            // Redirect back to search page with error
            header("Location: ../src/patientsRecordsSearch.php");
            exit;
        }
    } else {
        // No CIN provided
        $_SESSION['error_message'] = 'Patient CIN is required';
        header("Location: ../src/patientsRecordsSearch.php");
        exit;
    }
} catch (Exception $e) {
    // Store error in session
    $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
    
    // Log error in session for debugging
    if (!isset($_SESSION['errors'])) {
        $_SESSION['errors'] = [];
    }
    $_SESSION['errors'][] = [
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s'),
        'query_params' => [
            'cin' => $CIN ?? 'not set',
            'visit_day' => $VIST_date ?? 'not set'
        ]
    ];
    
    // Redirect back to search page
    header("Location: ../src/patientsRecordsSearch.php");
    exit;
} finally {
    $conn->close();
}
?>