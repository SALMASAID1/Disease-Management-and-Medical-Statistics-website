<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title></title>
  <link rel="stylesheet" href="../css/styleDoctorsPage.css" />
  <script type="text/javascript" src="../js/userDoctorsMenu.js" defer></script>
  <script type="text/javascript" src="../js/notification.js" defer></script>

</head>

<body>
  <!-- header section -->
  <div class="header-main-div">
    <img src="../images/doctor_section/image_1.png" class="header-img-logo" />
    <!-- <button class="header-button-logout">Logout</button> -->
    <div class="header-links">
      <h4>
        <a class="links-style" href="#about-Hospital-Statistics">Hospital Statistics</a>
      </h4>
      <h4>
        <a class="links-style" href="#about-Patient-History">Consult patient records</a>
      </h4>
      <h4>
        <a class="links-style" href="#about-Urgent-Appointments">Urgent Appointments</a>
      </h4>
    </div>
    <div class="header-div-2 header-button">
      <button
        id="userDropdownButton" aria-haspopup="true" aria-expanded="false" >
        <img
          src="../images/doctor_section/user-doctor.png"

          class="header-img-user" />
      </button>
      <button class="notification-bell" id="notification-bell">
        <div class="bell-icon-container">
          <img
            src="../images/doctor_section/Notification.png"
            class="header-img-notification" />
          <span class="notification-count" id="notification-count"></span>
        </div>
      </button>
      <div class="doctorInfo" role="menu" id="userDropdownMenu" aria-hidden="true" >
        <ul class="userMenu" > <!-- unordered list  -->
          <?php
          echo "<li role='menuitem'>{$_GET['Doctor']}</li>";
          echo "<!-- <li>Specialization: Cardiology</li> -->";
          echo "<li role='menuitem'><a href='../data/logout.php' style ='text-decoration: none;' >Logout</a></li>";
          ?>
        </ul>
      </div>
    </div>
  </div>

  <section class="notification-panel" id="notification-panel">
    <button class="close-button" id="close-button">✕</button>
    <h2 class="panel-title">Notifications</h2>
    <div class="notification-list" id="notification-list">
      <!-- Notifications will be dynamically inserted here -->
    </div>
  </section>

  <!-- content -->
  <div class="content-main-div">
    <br />
    <p id="demo">
      Access your medical dashboard to manage patient care and hospital
      operations
    </p>
    <!-- <button type="button" onclick='document.getElementById("demo").innerHTML = "Hello world" '>change me </button >  this a button on javascript -->
    <img
      class="content-main-div-2-img-doctor"
      src="../images/doctor_section/doctors_main_image_1.png" />

    <main class="cards-container">
      <div class="content-div-cards-rows" space="30">
        <!-- first-column -->
        <section class="card-column" >
          <div class="card" id="about-Hospital-Statistics"> 
            <h2 class="card-title">Hospital Statistics</h2>
            <p class="card-description">
              Access comprehensive statistics about patient care and hospital
              performance
            </p>
            <button class="btn btn-primary"><a href="./hospitalStatisticsPage.html" class="link-from-cards">View Statistics</a></button>
          </div>
        </section>
        <!-- second-colimn  -->

        <section class="card-column" >
          <div class="card" id="about-Patient-History">
            <h2 class="card-title">Consult patient records</h2>
            <p class="card-description">
              View detailed patient records and medical history
            </p>
            <button class="btn"><a href="./patientsRecordsSearch.php" class="link-from-cards">Access Records</a></button>
          </div>
        </section>

        <!-- Third-column -->

        <section class="card-column" >
          <div class="card" id="about-Urgent-Appointments">
            <h2 class="card-title">Urgent Appointments</h2>
            <p class="card-description">manage patient Urgent appointments</p>
            <button class="btn" id="openModalBtn"><a href="./UrgentAppointmentsStatus.html" class="link-from-cards">Manage Schedule</a></button>
          </div>
        </section>
      </div>
    </main>
  </div>

  <template id="notification-template">
    <article class="notification-item">
      <h3 class="patient-name"></h3>
      <p class="appointment-time">
        <span>Date:</span>
        <span class="date"></span>
      </p>
      <p class="message"></p>
      <div class="action-buttons">
        <button class="accept-button">Accept</button>
        <button class="reject-button">Reject</button>
      </div>
      <p class="status-text"></p>
    </article>
  </template>
  
  <!-- Footer -->
  <!-- <div class="footer">Footer</div> -->

  
</body>

</html>