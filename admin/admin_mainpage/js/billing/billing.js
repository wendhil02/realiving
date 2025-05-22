document.addEventListener("DOMContentLoaded", function () {
  // Payment Modal Controls
  const paymentModal = document.getElementById("paymentModal");
  const openPaymentModalBtn = document.getElementById("openPaymentModal");
  const closePaymentModalBtn = document.getElementById("closePaymentModal");
  const cancelPaymentBtn = document.getElementById("cancelPayment");

  openPaymentModalBtn.addEventListener("click", function () {
    paymentModal.classList.remove("hidden");
    setTimeout(() => {
      paymentModal.classList.add("opacity-100");
    }, 10);
  });

  function closePaymentModal() {
    paymentModal.classList.add("hidden");
  }

  closePaymentModalBtn.addEventListener("click", closePaymentModal);

  // Close modal when clicking outside
  paymentModal.addEventListener("click", function (event) {
    if (event.target === paymentModal) {
      closePaymentModal();
    }
  });

  // Notification function
  window.showNotification = function (message, type = "info") {
    const notification = document.getElementById("notification");
    const notificationMessage = document.getElementById("notificationMessage");

    notificationMessage.textContent = message;

    // Set border color based on notification type
    if (type === "error") {
      notification.classList.remove("border-primary-500");
      notification.classList.add("border-red-500");
      document
        .querySelector("#notification .fas")
        .classList.remove("text-primary-500");
      document
        .querySelector("#notification .fas")
        .classList.add("text-red-500");
    } else {
      notification.classList.remove("border-red-500");
      notification.classList.add("border-primary-500");
      document
        .querySelector("#notification .fas")
        .classList.remove("text-red-500");
      document
        .querySelector("#notification .fas")
        .classList.add("text-primary-500");
    }

    notification.classList.remove("hidden");

    // Auto hide after 5 seconds
    setTimeout(() => {
      closeNotification();
    }, 5000);
  };

  window.closeNotification = function () {
    const notification = document.getElementById("notification");
    notification.classList.add("hidden");
  };
});

// Project Cost Modal Management
document.addEventListener("DOMContentLoaded", function () {
  const editProjectCostBtn = document.getElementById("editProjectCostBtn");
  const editProjectCostModal = document.getElementById("editProjectCostModal");
  const closeProjectCostModal = document.getElementById(
    "closeProjectCostModal"
  );
  const confirmProjectCostBtn = document.getElementById(
    "confirmProjectCostBtn"
  );
  const confirmProjectCostModal = document.getElementById(
    "confirmProjectCostModal"
  );
  const cancelProjectCostUpdate = document.getElementById(
    "cancelProjectCostUpdate"
  );
  const newProjectCostInput = document.getElementById("new_project_cost");
  const confirmNewCostSpan = document.getElementById("confirmNewCost");
  const confirmNewProjectCostInput = document.getElementById(
    "confirm_new_project_cost"
  );

  // Open edit project cost modal
  if (editProjectCostBtn) {
    editProjectCostBtn.addEventListener("click", function () {
      editProjectCostModal.classList.remove("hidden");
      document.body.style.overflow = "hidden";
    });
  }

  // Close edit project cost modal
  if (closeProjectCostModal) {
    closeProjectCostModal.addEventListener("click", function () {
      editProjectCostModal.classList.add("hidden");
      document.body.style.overflow = "auto";
    });
  }

  // Show confirmation modal
  if (confirmProjectCostBtn) {
    confirmProjectCostBtn.addEventListener("click", function () {
      const newCost = parseFloat(newProjectCostInput.value);

      if (isNaN(newCost) || newCost < 0) {
        alert("Please enter a valid project cost amount.");
        return;
      }

      // Update confirmation modal with new cost
      confirmNewCostSpan.textContent =
        "₱" +
        newCost.toLocaleString("en-US", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        });
      confirmNewProjectCostInput.value = newCost;

      // Hide edit modal and show confirmation modal
      editProjectCostModal.classList.add("hidden");
      confirmProjectCostModal.classList.remove("hidden");
    });
  }

  // Cancel project cost update
  if (cancelProjectCostUpdate) {
    cancelProjectCostUpdate.addEventListener("click", function () {
      confirmProjectCostModal.classList.add("hidden");
      editProjectCostModal.classList.remove("hidden");
    });
  }

  // Close modals when clicking outside
  [editProjectCostModal, confirmProjectCostModal].forEach((modal) => {
    if (modal) {
      modal.addEventListener("click", function (e) {
        if (e.target === modal) {
          modal.classList.add("hidden");
          document.body.style.overflow = "auto";
        }
      });
    }
  });

  // Close modals with Escape key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      if (!confirmProjectCostModal.classList.contains("hidden")) {
        confirmProjectCostModal.classList.add("hidden");
        editProjectCostModal.classList.remove("hidden");
      } else if (!editProjectCostModal.classList.contains("hidden")) {
        editProjectCostModal.classList.add("hidden");
        document.body.style.overflow = "auto";
      }
    }
  });
});
