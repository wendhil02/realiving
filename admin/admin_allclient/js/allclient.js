        // Auto-hide flash messages
        setTimeout(function() {
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');
            
            if (successAlert) {
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }
            
            if (errorAlert) {
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 500);
            }
        }, 5000);

        // Modal functions
        function openEditModal(id, clientname, status, nameproject, client_type, client_class, contact, email, country, address, gender) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_clientname').value = clientname;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_nameproject').value = nameproject;
            document.getElementById('edit_client_type').value = client_type;
            document.getElementById('edit_client_class').value = client_class;
            document.getElementById('edit_contact').value = contact;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_country').value = country;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_gender').value = gender;
            
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
            }
        });

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            // Add any additional form validation here if needed
            console.log('Client Management System loaded successfully');
        });