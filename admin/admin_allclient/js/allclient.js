        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('#success-alert, #error-alert');
            alerts.forEach(alert => {
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 500);
                }
            });
        }, 5000);

        // Edit modal functionality
        function openEditModal(id, clientname, status, nameproject, client_type, client_class, contact, country, address, gender) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_clientname').value = clientname;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_nameproject').value = nameproject;
            document.getElementById('edit_client_type').value = client_type;
            document.getElementById('edit_client_class').value = client_class;
            document.getElementById('edit_contact').value = contact;
            document.getElementById('edit_country').value = country;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_gender').value = gender;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }