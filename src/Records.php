<?php
session_start();
// Make sure we have patient data, otherwise redirect
if (!isset($_SESSION['current_patient'])) {
    header("Location: patientsRecordsSearch.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Patient Records</title>
    <link rel="stylesheet" href="../css/stylesRecords.css" />
  </head>
  <body>
    <header class="header">
      <div class="header-content">
        <h1 class="page-title">Patient Records</h1>
        <a href="patientsRecordsSearch.php" class="link-back">
          Back to Search
        </a>
      </div>
    </header>

    <main class="dashboard-container">
      <div class="dashboard-content">
        <!-- Status message -->
        <div id="statusMessage" class="status-message"></div>
        
        <div id="recordsContent">
          <!-- Patient personal information section -->
          <section class="patient-info-card">
            <h2 class="section-title">Patient Information</h2>
            <div class="patient-info-grid">
              <div class="info-column">
                <div class="info-field">
                  <label class="field-label">CIN:</label>
                  <p class="field-value">
                    <?php echo htmlspecialchars($_SESSION['current_patient']['cin'] ?? ''); ?>
                  </p>
                </div>
                <div class="info-field">
                  <label class="field-label">Full Name:</label>
                  <p class="field-value">
                    <?php echo htmlspecialchars($_SESSION['current_patient']['full_name'] ?? ''); ?>
                  </p>
                </div>
                <div class="info-field">
                  <label class="field-label">Blood Type:</label>
                  <p class="field-value">
                    <?php echo htmlspecialchars($_SESSION['current_patient']['blood_type'] ?? ''); ?>
                  </p>
                </div>
              </div>
              <div class="info-column">
                <div class="info-field">
                  <label class="field-label">Age:</label>
                  <p class="field-value">
                    <?php echo htmlspecialchars($_SESSION['current_patient']['age'] ?? ''); ?>
                  </p>
                </div>
                <div class="info-field">
                  <label class="field-label">Sex:</label>
                  <p class="field-value">
                    <?php echo htmlspecialchars($_SESSION['current_patient']['sexe'] ?? ''); ?>
                  </p>
                </div>
                <div class="info-field">
                  <label class="field-label">Phone:</label>
                  <p class="field-value">
                    <?php echo htmlspecialchars($_SESSION['current_patient']['phone'] ?? ''); ?>
                  </p>
                </div>
                <div class="info-field">
                  <label class="field-label">Email:</label>
                  <p class="field-value">
                    <?php echo htmlspecialchars($_SESSION['current_patient']['email'] ?? ''); ?>
                  </p>
                </div>
              </div>
            </div>
          </section>

          <!-- Section divider -->
          <div class="section-divider">
            <span>Medical Records</span>
          </div>

          <!-- Treatment records section -->
          <section class="treatment-card">
            <h2 class="treatment-title">Treatment History</h2>
            <div id="treatmentsContainer">
              <?php 
              // Check if we have treatments
              if (isset($_SESSION['patient_treatments']) && !empty($_SESSION['patient_treatments'])) {
                  // Loop through each treatment and display it
                  foreach ($_SESSION['patient_treatments'] as $treatment) {
                      ?>
                      <div class="treatment-summary">
                          <p class="visit-date">
                              <span class="bold-label">Visit Date:</span>
                              <span><?php echo htmlspecialchars($treatment['visit_day'] ?? ''); ?></span>
                          </p>
                          <p class="symptoms">
                              <span class="bold-label">Symptoms:</span>
                              <span><?php echo htmlspecialchars($treatment['symptoms'] ?? ''); ?></span>
                          </p>
                          <p class="diagnostics">
                              <span class="bold-label">Diagnostics:</span>
                              <span><?php echo htmlspecialchars($treatment['diagnostics'] ?? ''); ?></span>
                          </p>
                          <div class="medicines-section">
                              <h3 class="medicines-title">Prescribed Medicine</h3>
                              <div class="medicine-details">
                                  <p class="medicine-name"><?php echo htmlspecialchars($treatment['medicine'] ?? ''); ?></p>
                                  <p><span class="bold-label">Dosage:</span> <?php echo htmlspecialchars($treatment['dosage'] ?? ''); ?></p>
                                  <p><span class="bold-label">Duration:</span> <?php echo htmlspecialchars($treatment['duration'] ?? ''); ?></p>
                              </div>
                          </div>
                      </div>
                      <?php
                  }
              } else {
                  // No treatments found
                  echo '<p class="treatment-summary">No treatment records available for this patient.</p>';
              }
              ?>
            </div>
          </section>
        </div>
      </div>
    </main>
  </body>
</html>
