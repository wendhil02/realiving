      document.addEventListener('DOMContentLoaded', function() {
            // Set Date and Time on Load
            const today = new Date();
            const dateString = today.toISOString().split('T')[0]; // Current date in YYYY-MM-DD format
            const timeString = today.toTimeString().split(' ')[0].slice(0, 5); // Current time in HH:MM format

            // Set date and time to the form inputs
            document.querySelector('[name="update_date"]').value = dateString;
            document.querySelector('[name="update_time"]').value = timeString;

            // Step-related fields
            const stepSelect = document.getElementById('stepSelect');
            const endDateContainer = document.getElementById('endDateContainer');
            const descriptionContainer = document.getElementById('descriptionContainer');
            const revisionContainer = document.getElementById('revisionContainer');

            // Toggle visibility of fields based on selected step
            function toggleFields() {
                const selectedStep = parseInt(stepSelect.value);

                // Show "End of Date" for steps 3 to 10
                if (selectedStep >= 3 && selectedStep <= 10) {
                    endDateContainer.classList.remove('hidden');
                } else {
                    endDateContainer.classList.add('hidden');
                }

                // Show "Description" only for Order Processing (step 6)
                if (selectedStep === 6) {
                    descriptionContainer.classList.remove('hidden');
                } else {
                    descriptionContainer.classList.add('hidden');
                }

                // Show "Revision Count" only for step 4
                if (selectedStep === 4) {
                    revisionContainer.classList.remove('hidden');
                } else {
                    revisionContainer.classList.add('hidden');
                }
            }

            // Add event listener for step selection change
            if (stepSelect) {
                stepSelect.addEventListener('change', toggleFields);
                // Run on page load to set initial state
                toggleFields();
            }


            // Show Notification function
            function showNotification(message, type = 'success') {
                const notification = document.getElementById('notification');
                const notificationMessage = document.getElementById('notificationMessage');

                if (notification && notificationMessage) {
                    // Set the message
                    notificationMessage.textContent = message;

                    // Apply appropriate styling based on the message type
                    if (type === 'error') {
                        notification.classList.remove('border-primary-500');
                        notification.classList.add('border-red-500');
                        document.querySelector('#notification i').classList.remove('text-primary-500');
                        document.querySelector('#notification i').classList.add('text-red-500');
                        document.querySelector('#notification i').classList.remove('fa-info-circle');
                        document.querySelector('#notification i').classList.add('fa-exclamation-circle');
                    } else {
                        notification.classList.remove('border-red-500');
                        notification.classList.add('border-primary-500');
                        document.querySelector('#notification i').classList.remove('text-red-500');
                        document.querySelector('#notification i').classList.add('text-primary-500');
                        document.querySelector('#notification i').classList.remove('fa-exclamation-circle');
                        document.querySelector('#notification i').classList.add('fa-info-circle');
                    }

                    // Show notification
                    notification.classList.remove('hidden');

                    // Auto hide after 5 seconds
                    setTimeout(() => {
                        notification.classList.add('hidden');
                    }, 5000);
                }
            }

            // Close notification manually
            function closeNotification() {
                document.getElementById('notification').classList.add('hidden');
            }

            // Enable edit mode for description
            function enableEdit(id) {
                document.getElementById('desc-display-' + id).classList.add('hidden');
                document.getElementById('desc-form-' + id).classList.remove('hidden');
                document.getElementById('desc-input-' + id).focus();
            }

            // Cancel the edit mode and revert back
            function cancelEdit(id) {
                document.getElementById('desc-display-' + id).classList.remove('hidden');
                document.getElementById('desc-form-' + id).classList.add('hidden');
            }
        });