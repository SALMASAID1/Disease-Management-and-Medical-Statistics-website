(() => {
  // State management
  const state = {
    isNotificationOpen: false,
    notifications: [], // Start with an empty array
    toggleNotifications() {
      state.isNotificationOpen = !state.isNotificationOpen;
      updateUI();
    },
    handleNotification(id, action) {
      state.notifications = state.notifications.map((notif) =>
        notif.id === id
          ? {
              ...notif,
              status: action,
            }
          : notif
      );
      updateUI();
    },
  };

  // DOM Elements
  const notificationBell = document.getElementById("notification-bell");
  const notificationCount = document.getElementById("notification-count");
  const notificationPanel = document.getElementById("notification-panel");
  const closeButton = document.getElementById("close-button");
  const notificationList = document.getElementById("notification-list");
  const notificationTemplate = document.getElementById("notification-template");

  // Event Listeners
  notificationBell.addEventListener("click", () => {
    state.toggleNotifications();
  });

  closeButton.addEventListener("click", () => {
    state.toggleNotifications();
  });

  // Update UI based on state
  function updateUI() {
    // Update notification count
    const pendingCount = state.notifications.filter(
      (n) => n.status === "pending"
    ).length;
    notificationCount.textContent = pendingCount;

    // Toggle notification panel
    if (state.isNotificationOpen) {
      notificationPanel.classList.add("open");
    } else {
      notificationPanel.classList.remove("open");
    }

    renderNotifications();
  }

  function renderNotifications() {
    // Clear existing notifications
    notificationList.innerHTML = "";

    // Render each notification
    state.notifications.forEach((notification) => {
      const notificationElement = notificationTemplate.content.cloneNode(true);

      // Set notification data
      notificationElement.querySelector(".patient-name").textContent =
        notification.patientName;
      notificationElement.querySelector(".date").textContent =
        notification.date;
      notificationElement.querySelector(".message").textContent =
        notification.message;

      const notificationItem =
        notificationElement.querySelector(".notification-item");
      const actionButtons =
        notificationElement.querySelector(".action-buttons");
      const statusText = notificationElement.querySelector(".status-text");

      // Handle notification status
      if (notification.status === "pending") {
        actionButtons.style.display = "flex";
        statusText.style.display = "none";
      } else {
        actionButtons.style.display = "none";
        statusText.textContent = notification.status;
        statusText.classList.add(notification.status);
        notificationItem.classList.add("inactive");
      }

      // Add event listeners to buttons
      const acceptButton = notificationElement.querySelector(".accept-button");
      const rejectButton = notificationElement.querySelector(".reject-button");

      acceptButton.addEventListener("click", () => {
        updateNotificationStatus(notification.id, "accepted");
      });

      rejectButton.addEventListener("click", () => {
        updateNotificationStatus(notification.id, "rejected");
      });

      // Add notification to the list
      notificationList.appendChild(notificationElement);
    });
  }

  // Fetch notifications from the PHP file
  async function fetchNotifications() {
    try {
      const response = await fetch(
        "http://localhost/MedicalWeb/data/UrgentAppointmentsData.php"
      );
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json(); // Parse JSON data
      updateStateWithNotifications(data); // Update the state with fetched data
    } catch (error) {
      console.error("Failed to fetch notifications:", error);
    }
  }

  // Update the state with fetched notifications
  function updateStateWithNotifications(notifications) {
    state.notifications = notifications.map((notif) => ({
      id: notif.id, // Use the id from the database
      patientName: notif.full_name,
      date: notif.date,
      message: notif.message,
      status: notif.status,
    }));
    updateUI(); // Re-render the UI with the new data
  }

  // Function to update notification status in the database
  async function updateNotificationStatus(id, status) {
    try {
      const response = await fetch(
        "http://localhost/MedicalWeb/data/UpdateAppointmentStatus.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ id, status }),
        }
      );

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const result = await response.json();
      if (result.success) {
        // Update the state and UI
        state.handleNotification(id, status);
      } else {
        console.error("Failed to update status:", result.error);
      }
    } catch (error) {
      console.error("Error updating notification status:", error);
    }
  }

  // Fetch notifications and initialize the UI
  fetchNotifications();
})();
