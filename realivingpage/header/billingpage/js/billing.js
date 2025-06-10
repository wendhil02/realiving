  // Streamlined billing form functions - essential functions only

        function fetchDetails() {
            const code = document.getElementById("reference").value;
            
            if (code.trim() === "") return;
           
            fetch("fetch_user_info.php?code=" + encodeURIComponent(code))
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById("clientname").value = data.clientname;
                        document.getElementById("total_cost").value = data.total_project_cost;
                        document.getElementById("remaining_balance").value = data.remaining_balance;
                        showNotification("Billing code found! Client details loaded successfully.", "success");
                    } else {
                        showNotification("Billing code not found. Please check and try again.", "error");
                        clearClientFields();
                    }
                })
                .catch(err => {
                    console.error("Fetch error: ", err);
                    showNotification("Connection error. Please try again.", "error");
                });
        }

        function clearClientFields() {
            document.getElementById("clientname").value = "";
            document.getElementById("total_cost").value = "";
            document.getElementById("remaining_balance").value = "";
        }

        // File upload handlers
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                displayImagePreview(file);
            } else if (file) {
                showNotification("Please upload only image files.", "warning");
                event.target.value = '';
            }
        }

        function displayImagePreview(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('uploadContent').classList.add('hidden');
                document.getElementById('imagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function removeImage() {
            document.getElementById('receipt').value = '';
            document.getElementById('uploadContent').classList.remove('hidden');
            document.getElementById('imagePreview').classList.add('hidden');
        }

        // Drag and drop handlers
        function dragOverHandler(ev) {
            ev.preventDefault();
        }

        function dragEnterHandler(ev) {
            ev.preventDefault();
            document.getElementById('dropZone').classList.add('border-blue-400', 'bg-blue-50');
        }

        function dragLeaveHandler(ev) {
            ev.preventDefault();
            document.getElementById('dropZone').classList.remove('border-blue-400', 'bg-blue-50');
        }

        function dropHandler(ev) {
            ev.preventDefault();
            document.getElementById('dropZone').classList.remove('border-blue-400', 'bg-blue-50');
            
            const files = ev.dataTransfer.files;
            if (files.length > 0 && files[0].type.startsWith('image/')) {
                document.getElementById('receipt').files = files;
                displayImagePreview(files[0]);
            } else if (files.length > 0) {
                showNotification("Please upload only image files.", "warning");
            }
        }

        // Form reset
        function resetForm() {
            document.getElementById('billingForm').reset();
            clearClientFields();
            removeImage();
            document.getElementById('reference').focus();
        }

        // Confirmation dialog function
        function showConfirmationDialog(message, onConfirm, onCancel) {
            // Create modal overlay
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.id = 'confirmationModal';
            
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-xl p-6 max-w-md mx-4 transform transition-all">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Confirm Submission</h3>
                    </div>
                    <p class="text-gray-600 mb-6">${message}</p>
                    <div class="flex space-x-3">
                        <button id="confirmYes" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            Yes, Submit
                        </button>
                        <button id="confirmNo" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Add event listeners
            document.getElementById('confirmYes').onclick = function() {
                document.body.removeChild(modal);
                onConfirm();
            };
            
            document.getElementById('confirmNo').onclick = function() {
                document.body.removeChild(modal);
                if (onCancel) onCancel();
            };
            
            // Close on background click
            modal.onclick = function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                    if (onCancel) onCancel();
                }
            };
        }

        // Form submission with confirmation
        function submitForm(event) {
            event.preventDefault();
            
            const form = document.getElementById('billingForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const fileInput = document.getElementById('receipt');
            if (!fileInput.files || fileInput.files.length === 0) {
                showNotification('Please upload a receipt image before submitting.', 'warning');
                return;
            }

            // Show confirmation dialog
            showConfirmationDialog(
                'Are you sure you want to submit this receipt? Once submitted, it will be reviewed by the admin.',
                function() {
                    // User confirmed - proceed with submission
                    const formData = new FormData(form);
                    const submitBtn = document.getElementById('submitBtn');
                    const submitText = document.getElementById('submitText');
                    const loadingSpinner = document.getElementById('loadingSpinner');
                    
                    // Show loading state
                    submitBtn.disabled = true;
                    submitText.textContent = 'Submitting...';
                    if (loadingSpinner) loadingSpinner.classList.remove('hidden');

                    fetch('submit_billing.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Receipt submitted successfully!', 'success');
                            removeImage(); // Only clear the uploaded image
                        } else {
                            showNotification('Error: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred while submitting the form.', 'error');
                    })
                    .finally(() => {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitText.textContent = 'Submit Receipt';
                        if (loadingSpinner) loadingSpinner.classList.add('hidden');
                    });
                },
                function() {
                    // User cancelled - do nothing
                    console.log('Submission cancelled by user');
                }
            );
        }

        // Simple notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm`;
            
            const colors = {
                'success': 'bg-green-500 text-white',
                'error': 'bg-red-500 text-white',
                'warning': 'bg-yellow-500 text-white',
                'info': 'bg-blue-500 text-white'
            };
            
            notification.className += ' ' + (colors[type] || colors['info']);
            
            notification.innerHTML = `
                <div class="flex items-start">
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        ✕
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto-remove after 4 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 4000);
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus on reference field
            const referenceInput = document.getElementById('reference');
            if (referenceInput) {
                setTimeout(() => {
                    referenceInput.focus();
                }, 100);
            }
            
            // Set up form submission
            const form = document.getElementById('billingForm');
            if (form) {
                form.addEventListener('submit', submitForm);
            }
        });