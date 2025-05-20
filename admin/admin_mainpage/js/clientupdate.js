  // Function to show notification and auto-close after a few seconds
  function showNotification(message, type) {
    const notification = document.getElementById('notification');
    const notificationText = document.getElementById('notificationText');

    // Set message and style based on notification type (success or error)
    notificationText.textContent = message;
    if (type === 'success') {
        notification.classList.remove('bg-red-500', 'hidden');
        notification.classList.add('bg-green-500');
    } else if (type === 'error') {
        notification.classList.remove('bg-green-500', 'hidden');
        notification.classList.add('bg-red-500');
    }

    // Show notification
    notification.classList.remove('hidden');

    // Auto-close notification after 3 seconds
    setTimeout(() => {
        notification.classList.add('hidden');
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function () {
  // Grab all the elements
  const openUpdateModalBtn = document.getElementById('openUpdateModal');
  const closeUpdateModalBtn = document.getElementById('closeUpdateModal');
  const updateModal         = document.getElementById('updateModal');
  const updateForm          = document.getElementById('updateForm');
  const saveButton          = document.getElementById('saveButton');
  const normalButtons       = document.getElementById('normalButtons');
  const confirmButtons      = document.getElementById('confirmButtons');
  const confirmNo           = document.getElementById('confirmNo');

  if (!openUpdateModalBtn || !closeUpdateModalBtn || !updateModal ||
      !updateForm || !saveButton || !normalButtons ||
      !confirmButtons || !confirmNo) {
    console.error('One or more update‑modal elements not found.');
    return;
  }


  // 1) Open modal
  openUpdateModalBtn.addEventListener('click', () => {
    updateModal.classList.remove('hidden');
    updateModal.classList.add('flex');
  });

  // 2) Close modal via Cancel button
  closeUpdateModalBtn.addEventListener('click', () => {
    updateModal.classList.remove('flex');
    updateModal.classList.add('hidden');
  });

  // 3) Close modal by clicking backdrop
  updateModal.addEventListener('click', (e) => {
    if (e.target === updateModal) {
      updateModal.classList.remove('flex');
      updateModal.classList.add('hidden');
    }
  });

  // 4) Swap to confirmation buttons on Save
  saveButton.addEventListener('click', () => {
    normalButtons.classList.add('hidden');
    confirmButtons.classList.remove('hidden');
  });

  // 5) Cancel confirmation
  confirmNo.addEventListener('click', () => {
    confirmButtons.classList.add('hidden');
    normalButtons.classList.remove('hidden');
  });

  // 6) Validate date/time before actual submit
  updateForm.addEventListener('submit', function (e) {
    const dateInput = document.getElementById('update_date');
    const timeInput = document.getElementById('update_time');

    if (!dateInput.value.trim() || !timeInput.value.trim()) {
      e.preventDefault();
      showNotification('Please fill in both date and time before saving.', 'error');
      // revert buttons
      confirmButtons.classList.add('hidden');
      normalButtons.classList.remove('hidden');
    }
  });

});


// Submit form kapag YES (no need to add listener kasi <button type="submit"> na yung YES)