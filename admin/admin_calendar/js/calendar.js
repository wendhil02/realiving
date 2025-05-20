   function openModal() {
            document.getElementById('appointmentModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function viewAppointments() {
            document.getElementById('appointmentsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModalAppointments() {
            document.getElementById('appointmentsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function toggleDone(checkbox, date, title) {
            const label = checkbox.nextElementSibling;
            const dayDiv = document.getElementById('day-' + date);
            
            const status = checkbox.checked ? 'done' : 'pending';

            // AJAX call to save the status
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_status.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('date=' + encodeURIComponent(date) + '&title=' + encodeURIComponent(title) + '&status=' + status);

            xhr.onload = function () {
                if (xhr.status === 200 && xhr.responseText === 'success') {
                    if (status === 'done') {
                        label.classList.add('text-green-600', 'line-through');
                        if (dayDiv) dayDiv.classList.add('done-date');
                    } else {
                        label.classList.remove('text-green-600', 'line-through');

                        const checkboxes = document.querySelectorAll('input[type="checkbox"][onchange*="' + date + '"]');
                        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                        if (!anyChecked && dayDiv) {
                            dayDiv.classList.remove('done-date');
                        }
                    }
                } else {
                    alert('Failed to update appointment status.');
                    checkbox.checked = !checkbox.checked; // Revert if failed
                }
            };
        }

        // Initialize page effects
        document.addEventListener('DOMContentLoaded', function() {
            // Set today's date in the add appointment form by default
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.querySelector('input[name="appointment_date"]');
            if (dateInput) {
                dateInput.value = today;
            }
        });