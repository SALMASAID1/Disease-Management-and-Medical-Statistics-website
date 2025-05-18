# MedicalWeb - Healthcare Management System

## Overview

MedicalWeb is a web application designed to manage patient medical records, appointments, and provide healthcare-related statistics. It offers separate functionalities for patients and healthcare providers.

## Features

*   **Patient Portal**:
    *   View personal information via [`showInformations.php`](MedicalWeb/src/showInformations.php).
    *   Request urgent appointments through [`patientApp.php`](MedicalWeb/src/patientApp.php) which submits to [`urgentAppointment.php`](MedicalWeb/src/urgentAppointment.php).
    *   View personal medical statistics via [`staticByPatient.php`](MedicalWeb/src/staticByPatient.php).
*   **Provider Portal** (Doctors):
    *   Access hospital-wide statistics through [`hospitalStatisticsPage.html`](MedicalWeb/src/hospitalStatisticsPage.html).
    *   Search and view patient records using [`patientsRecordsSearch.php`](MedicalWeb/src/patientsRecordsSearch.php) and display them via [`Records.php`](MedicalWeb/src/Records.php).
    *   Manage urgent appointment requests (details likely in `doctorsPage.php` and related scripts like `UrgentAppointmentsDashboard.php`).
*   **Medical Records**:
    *   Detailed patient information and treatment history accessible via [`Records.php`](MedicalWeb/src/Records.php).
    *   Data processing for records handled by [`checkPatientRecord.php`](MedicalWeb/data/checkPatientRecord.php).
*   **Data Visualization**:
    *   Patient-specific charts in [`staticByPatient.php`](MedicalWeb/src/staticByPatient.php) (e.g., dosage, diagnosis duration).
    *   Hospital statistics charts in [`hospitalStatisticsPage.html`](MedicalWeb/src/hospitalStatisticsPage.html) (e.g., age distribution, blood type distribution, appointment trends, diagnostic trends).
    *   Chart generation logic in files within the `MedicalWeb/charts/` directory (e.g., [`AgeDistribution.php`](MedicalWeb/charts/AgeDistribution.php), [`BloodDistribution.php`](MedicalWeb/charts/BloodDistribution.php), [`AppointmentChart.php`](MedicalWeb/charts/AppointmentChart.php), [`DiagnosticsLast7days.php`](MedicalWeb/charts/DiagnosticsLast7days.php)).

## Directory Structure

## Installation

### Prerequisites
*   A web server with PHP support (e.g., XAMPP, WAMP, MAMP).
*   A MySQL database server.
*   Web browser.

### Setup
1.  Clone or download the repository into your web server's document root (e.g., `htdocs` for XAMPP).
2.  Create a database (e.g., `medicaldata` as seen in various files like [`charts/test.php`](MedicalWeb/charts/test.php)).
3.  Import the database schema if provided (schema details are not fully available but tables like `patients`, `treatments`, `doctors`, `urgent_appointment` are used).
4.  Configure the database connection in `MedicalWeb/db/config.php` (and potentially other files like [`charts/test.php`](MedicalWeb/charts/test.php) if they don't use the central config).
    *   **Servername**: `localhost` (typical)
    *   **Username**: e.g., `root`
    *   **Password**: e.g., `Chicken@id1@@` (as seen in [`charts/test.php`](MedicalWeb/charts/test.php))
    *   **Database**: e.g., `medicaldata`
5.  Access the application through your web browser, typically by navigating to `http://localhost/MedicalWeb/public/index.html`.

## Usage

1.  Open the application in your web browser: `http://localhost/MedicalWeb/public/index.html`.
2.  The [`public/index.html`](MedicalWeb/public/index.html) page serves as the entry point.
3.  Log in using the form provided:
    *   Enter your **CIN**.
    *   Select your **Role** (Provider or Receiver).
    *   The login is processed by [`data/Login.php`](MedicalWeb/data/Login.php).
4.  Based on the role:
    *   **Patients (Receivers)** are redirected to [`src/patientApp.php`](MedicalWeb/src/patientApp.php).
    *   **Doctors (Providers)** are redirected to [`src/doctorsPage.php`](MedicalWeb/src/doctorsPage.php).

## Key Files

*   [`public/index.html`](MedicalWeb/public/index.html): Main landing and login page.
*   [`data/Login.php`](MedicalWeb/data/Login.php): Handles user authentication.
*   [`src/patientApp.php`](MedicalWeb/src/patientApp.php): Patient dashboard.
*   [`src/doctorsPage.php`](MedicalWeb/src/doctorsPage.php): Doctor dashboard.
*   [`src/patientsRecordsSearch.php`](MedicalWeb/src/patientsRecordsSearch.php): Interface for doctors to search patient records.
*   [`data/checkPatientRecord.php`](MedicalWeb/data/checkPatientRecord.php): Processes patient record search requests.
*   [`src/Records.php`](MedicalWeb/src/Records.php): Displays detailed patient records.
*   [`src/hospitalStatisticsPage.html`](MedicalWeb/src/hospitalStatisticsPage.html): Displays hospital-level statistics.
*   [`src/staticByPatient.php`](MedicalWeb/src/staticByPatient.php): Displays statistics for a specific patient.
*   [`db/config.php`](MedicalWeb/db/config.php): (Assumed) Main database configuration file.

## Technologies Used

*   **Backend**: PHP
*   **Frontend**: HTML, CSS, JavaScript
*   **Database**: MySQL
*   **Charting**: Google Charts (as seen in [`src/hospitalStatisticsPage.html`](MedicalWeb/src/hospitalStatisticsPage.html) and `js` files)
