<?php
session_start();
$cin_ = isset($_SESSION['cin_from_home_page']) ? $_SESSION['cin_from_home_page'] : null;
$rol_ = isset($_SESSION['role_from_home_page']) ? $_SESSION['role_from_home_page'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8" /> 
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <link
 href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css"
 rel="stylesheet"
/>
<link rel="stylesheet" href="../css/styles.css" />
<title>PatientApp</title>
</head>
<body>
    <header>
   <nav class="section__container nav__container">
    <img class="logo" src="../assests/imagesHomePage/logo.png"/>
    <ul class="nav__links">
        <?php
        echo "<li class='link'><a href='showInformations.php'>Show Informations</a></li>";
        echo " <li class='link'><a href='#urgent'>Urgent Appointment</a></li>";
        echo "<li class='link'><a href='staticByPatient.php'>Show Statistics</a></li>";
        ?>
    </ul>
   </nav>
   <div class="section__container header__container" id="home">
   <div class="header__content">
    <h1>Welcome to Your Space</h1>
    <p class="lines">
        Welcome to your personalized health space! We are committed to supporting your well-being by providing easy access to your medical records, appointments, and health resources. Our goal is to keep you informed and empowered in managing your health journey. With a seamless and secure experience, you can stay connected with your care team whenever you need. Your health is our priority, and we’re here to help you every step of the way.
    </p>

    </div>
    </header>

    <div class="form-container" id="urgent">
        <h2>Urgent Appointment</h2>
                <form method="post" action="urgentAppointment.php">

            <label for="date">Date</label>
            <input type="date" id="date" name="date" required>


            <label for="message">Message</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit">Request Appointment</button>
            
        </form>
    </div>
</body>
</html>