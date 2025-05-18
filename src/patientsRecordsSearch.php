<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Patient Search</title>
    <link rel="stylesheet" href="../css/stylePatientsRecords.css" />
  </head>

  <body>
    <div class="container-search-section">
      <form action="../data/checkPatientRecord.php" method="post">
        <div class="imageContainer">
          <img
            src="../assests/imagesPatientsRecords/userPatien.png"
            alt="iconPatient"
          />
        </div>
        <fieldset class="fieldsetPatient">
          <legend>Access Records</legend>
          
          <!-- Error message display -->
          <div id="errorMessage" class="error-message"></div>
          
          <div class="inputContainer">
            <label class="CINLable" for="CIN">CIN:</label>
            <input
              class="inputFieldsCIN"
              type="text"
              name="CIN"
              id="CIN"
              placeholder="EX: AX1234 or A1234"
              required
            />
          </div>
          <div class="inputContainer">
            <label for="visitDay">Visit day:</label>
            <input 
              class="inputFieldsDATE" 
              type="date"
              name="visitDay" 
              id="visitDay" 
            />
          </div>
          <button class="submit" type="submit">Access</button>
        </fieldset>
      </form>
    </div>

    <script>
      // Check for error message in session
      <?php
      if (isset($_SESSION['error_message'])) {
        echo "document.getElementById('errorMessage').textContent = '" . 
             addslashes($_SESSION['error_message']) . "';";
        echo "document.getElementById('errorMessage').style.display = 'block';";
        // Clear the error message
        unset($_SESSION['error_message']);
      }
      ?>
    </script>
  </body>
</html>
