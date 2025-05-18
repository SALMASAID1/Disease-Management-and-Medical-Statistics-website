document.addEventListener("DOMContentLoaded", function () {
  // Initial appointment data
  const state = {
    appointments: [],
    filter: "all",
    searchQuery: "",
  };

  // Cache DOM elements
  const tableBody = document.getElementById("appointments-body");
  const searchInput = document.getElementById("search-input");
  const filterButtons = document.querySelectorAll(".filter-btn");
  const noResultsElement = document.getElementById("no-results");

  // Function to render the appointments table based on current filter and search
  function renderAppointmentsTable() {
    tableBody.innerHTML = "";
    
    // Filter and search appointments
    const filteredAppointments = state.appointments.filter(appointment => {
      // Apply status filter
      const statusMatch = state.filter === "all" || appointment.status === state.filter;
      
      // Apply search query
      const query = state.searchQuery.toLowerCase();
      const nameMatch = appointment.patientName.toLowerCase().includes(query);
      const cinMatch = appointment.cin.toLowerCase().includes(query);
      
      return statusMatch && (nameMatch || cinMatch);
    });

    // Show or hide no results message
    if (filteredAppointments.length === 0) {
      noResultsElement.classList.remove("hidden");
    } else {
      noResultsElement.classList.add("hidden");
    }

    // Render filtered appointments
    filteredAppointments.forEach((appointment) => {
      const row = document.createElement("tr");
      row.className = "appointment-row";
      row.setAttribute("data-id", appointment.id);

      // Patient Name cell
      const nameCell = document.createElement("td");
      nameCell.className = "table-cell";
      nameCell.textContent = appointment.patientName;
      row.appendChild(nameCell);

      // CIN cell
      const cinCell = document.createElement("td");
      cinCell.className = "table-cell";
      cinCell.textContent = appointment.cin;
      row.appendChild(cinCell);

      // Message cell
      const messageCell = document.createElement("td");
      messageCell.className = "table-cell";
      messageCell.textContent = appointment.message;
      row.appendChild(messageCell);

      // Visit Date cell
      const dateCell = document.createElement("td");
      dateCell.className = "table-cell";
      
      // Format date for better readability
      const date = new Date(appointment.visitDate);
      const formattedDate = date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
      });
      
      dateCell.textContent = formattedDate;
      row.appendChild(dateCell);

      // Status cell
      const statusCell = document.createElement("td");
      statusCell.className = "table-cell";

      const statusBadge = document.createElement("span");
      statusBadge.className = `status-badge status-${appointment.status}`;
      statusBadge.textContent =
        appointment.status.charAt(0).toUpperCase() +
        appointment.status.slice(1);

      statusCell.appendChild(statusBadge);
      row.appendChild(statusCell);

      // Actions cell
      const actionsCell = document.createElement("td");
      actionsCell.className = "table-cell";

      const actionButtons = document.createElement("div");
      actionButtons.className = "action-buttons";

      // Accept button
      const acceptButton = document.createElement("button");
      acceptButton.className = "action-btn accept-btn";
      acceptButton.innerHTML = '<i class="fas fa-check"></i>';
      acceptButton.title = "Accept appointment";
      acceptButton.disabled = appointment.status === "accepted";
      acceptButton.addEventListener("click", () => {
        updateAppointmentStatus(appointment.id, "accepted");
      });

      // Reject button
      const rejectButton = document.createElement("button");
      rejectButton.className = "action-btn reject-btn";
      rejectButton.innerHTML = '<i class="fas fa-times"></i>';
      rejectButton.title = "Reject appointment";
      rejectButton.disabled = appointment.status === "rejected";
      rejectButton.addEventListener("click", () => {
        updateAppointmentStatus(appointment.id, "rejected");
      });

      actionButtons.appendChild(acceptButton);
      actionButtons.appendChild(rejectButton);
      actionsCell.appendChild(actionButtons);
      row.appendChild(actionsCell);

      tableBody.appendChild(row);
    });
  }

  // Handle filter button clicks
  filterButtons.forEach(button => {
    button.addEventListener("click", () => {
      // Remove active class from all buttons
      filterButtons.forEach(btn => btn.classList.remove("active"));
      
      // Add active class to clicked button
      button.classList.add("active");
      
      // Update state with new filter
      state.filter = button.getAttribute("data-filter");
      
      // Re-render table
      renderAppointmentsTable();
    });
  });

  // Handle search input
  searchInput.addEventListener("input", (e) => {
    state.searchQuery = e.target.value;
    renderAppointmentsTable();
  });

  // Fetch appointments from the backend
  async function fetchAppointments() {
    try {
      const response = await fetch("http://localhost/MedicalWeb/data/UrgentAppointmentsDashboard.php");
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
      
      // Check if there's an error
      if (data.error) {
        console.error("Error fetching appointments:", data.error);
        return;
      }

      // Combine all appointments into a single array
      state.appointments = [
        ...data.pending,
        ...data.accepted,
        ...data.rejected
      ];

      // Render the table
      renderAppointmentsTable();
    } catch (error) {
      console.error("Failed to fetch appointments:", error);
    }
  }

  // Update appointment status in both UI and backend
  async function updateAppointmentStatus(id, newStatus) {
    // Find the appointment in the state
    const appointment = state.appointments.find((app) => app.id === parseInt(id));
    
    if (!appointment) {
      console.error(`Appointment with ID ${id} not found`);
      return;
    }

    try {
      // Send the update request to the backend
      const response = await fetch("http://localhost/MedicalWeb/data/UpdateAppointmentStatus.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id, status: newStatus }),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const result = await response.json();
      if (result.success) {
        // Update the status in the local state
        appointment.status = newStatus;
        
        // Show notification
        showNotification(`Appointment for ${appointment.patientName} marked as ${newStatus}`);
        
        // Re-render the table
        renderAppointmentsTable();
      } else {
        console.error("Failed to update appointment status:", result.error);
      }
    } catch (error) {
      console.error("Error updating appointment status:", error);
    }
  }

  // Function to display notification
  function showNotification(message) {
    // Create notification element
    const notification = document.createElement("div");
    notification.className = "notification";
    notification.textContent = message;
    
    // Add styles directly (could also be added to CSS)
    notification.style.position = "fixed";
    notification.style.bottom = "20px";
    notification.style.right = "20px";
    notification.style.backgroundColor = "#3498db";
    notification.style.color = "white";
    notification.style.padding = "15px 25px";
    notification.style.borderRadius = "8px";
    notification.style.boxShadow = "0 4px 12px rgba(0,0,0,0.15)";
    notification.style.zIndex = "1000";
    notification.style.transition = "all 0.3s ease";
    notification.style.opacity = "0";
    notification.style.transform = "translateY(20px)";
    
    // Add to document
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
      notification.style.opacity = "1";
      notification.style.transform = "translateY(0)";
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
      notification.style.opacity = "0";
      notification.style.transform = "translateY(20px)";
      
      setTimeout(() => {
        document.body.removeChild(notification);
      }, 300);
    }, 3000);
  }

  // Export functions for external use
  window.addAppointment = function (appointment) {
    // Generate a new unique ID
    const newId = Math.max(...state.appointments.map(app => app.id), 0) + 1;
    appointment.id = newId;
    
    // Add to state
    state.appointments.push(appointment);
    
    // Re-render
    renderAppointmentsTable();
    
    // Show notification
    showNotification("New appointment added successfully");
    
    return newId;
  };

  window.updateAppointmentStatus = updateAppointmentStatus;

  // Initial render
  renderAppointmentsTable();

  // Call fetchAppointments when the page loads
  fetchAppointments();
});