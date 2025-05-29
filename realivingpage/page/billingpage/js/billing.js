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
                document.getElementById("clientname").value = "";
                document.getElementById("total_cost").value = "";
                document.getElementById("remaining_balance").value = "";
            }
        })
        .catch(err => {
            console.error("Fetch error: ", err);
            showNotification("Connection error. Please try again.", "error");
        });
}

// File upload handlers
function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        displayImagePreview(file);
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
    }
}

// Reset form function
function resetForm() {
    // Reset the form
    document.getElementById('billingForm').reset();
    
    // Clear all input fields
    document.getElementById('reference').value = '';
    document.getElementById('clientname').value = '';
    document.getElementById('total_cost').value = '';
    document.getElementById('remaining_balance').value = '';
    
    // Reset image preview
    removeImage();
    
    // Focus back to reference number field
    document.getElementById('reference').focus();
    
    showNotification("Form has been reset successfully.", "info");
}

// Show loading state
function showLoading() {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    submitBtn.disabled = true;
    submitBtn.classList.remove('hover:bg-green-600');
    submitBtn.classList.add('bg-green-400', 'cursor-not-allowed');
    submitText.textContent = 'Submitting...';
    loadingSpinner.classList.remove('hidden');
}

// Hide loading state
function hideLoading() {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    submitBtn.disabled = false;
    submitBtn.classList.add('hover:bg-green-600');
    submitBtn.classList.remove('bg-green-400', 'cursor-not-allowed');
    submitText.textContent = 'Submit Receipt';
    loadingSpinner.classList.add('hidden');
}

// Form submission with 2-second loading
function submitForm(event) {
    event.preventDefault();
    
    // Show loading state immediately
    showLoading();
    
    const formData = new FormData(document.getElementById('billingForm'));
    
    // Add 2-second delay for loading effect
    setTimeout(() => {
        fetch('submit_billing.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Receipt submitted successfully! You can submit more receipts for this billing code.', 'success');
                // Only reset the receipt image, keep other fields intact for multiple submissions
                resetReceiptOnly();
            } else {
                showNotification('Error submitting form: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while submitting the form.', 'error');
        })
        .finally(() => {
            // Hide loading state
            hideLoading();
        });
    }, 2000); // 2-second delay
}

// Reset only the receipt image for multiple submissions
function resetReceiptOnly() {
    // Only reset the image upload
    removeImage();
    
    // Keep the billing details intact for additional submissions
    // Focus back to file upload for next receipt
    document.getElementById('dropZone').scrollIntoView({ behavior: 'smooth' });
}

// Global variable to store form data
let currentFormData = null;

function handleFile(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('uploadContent').classList.add('hidden');
        document.getElementById('imagePreview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function showPaymentProof(event) {
    event.preventDefault();
    
    // Validate form first
    const form = document.getElementById('billingForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Check if file is uploaded
    const fileInput = document.getElementById('receipt');
    if (!fileInput.files || fileInput.files.length === 0) {
        showNotification('Please upload a receipt image before submitting.', 'warning');
        return;
    }

    // Store form data globally
    currentFormData = new FormData(form);
    
    // Populate modal with form data
    document.getElementById('proofReference').textContent = document.getElementById('reference').value;
    document.getElementById('proofClientName').textContent = document.getElementById('clientname').value;
    document.getElementById('proofTotalCost').textContent = document.getElementById('total_cost').value;
    document.getElementById('proofRemainingBalance').textContent = document.getElementById('remaining_balance').value;
    document.getElementById('proofFileName').textContent = fileInput.files[0].name;
    
    // Set timestamp
    const now = new Date();
    document.getElementById('proofTimestamp').textContent = 
        `Submitted on ${now.toLocaleDateString()} at ${now.toLocaleTimeString()}`;
    
    // Show modal
    document.getElementById('paymentProofModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeModal() {
    document.getElementById('paymentProofModal').classList.add('hidden');
    document.body.style.overflow = ''; // Restore scrolling
    currentFormData = null; // Clear stored data
}

function takeScreenshot() {
    // Create a custom notification modal instead of alert
    const screenshotModal = document.createElement('div');
    screenshotModal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50';
    screenshotModal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
            <div class="text-center mb-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Take Screenshot Instructions</h3>
            </div>
            <div class="space-y-3 text-sm text-gray-600 mb-6">
                <div class="flex items-center">
                    <span class="font-semibold text-blue-600 mr-2">Windows:</span>
                    <span>Press Windows Key + Shift + S</span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold text-green-600 mr-2">Mac:</span>
                    <span>Press Cmd + Shift + 4</span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold text-purple-600 mr-2">Mobile:</span>
                    <span>Use your device's screenshot function</span>
                </div>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                <p class="text-sm text-yellow-800 font-medium">💡 Save the screenshot as proof of your submission!</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" 
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200">
                Got it!
            </button>
        </div>
    `;
    document.body.appendChild(screenshotModal);
    
    // Auto-remove after 10 seconds
    setTimeout(() => {
        if (screenshotModal.parentElement) {
            screenshotModal.remove();
        }
    }, 10000);
}

function proceedWithSubmission() {
    if (!currentFormData) {
        showNotification('No form data available. Please try again.', 'error');
        return;
    }

    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    submitBtn.disabled = true;
    submitText.textContent = 'Submitting...';
    loadingSpinner.classList.remove('hidden');

    // Simulate API submission
    setTimeout(() => {
        // Simulate successful submission
        showNotification('Receipt submitted successfully! You will receive an email confirmation once reviewed by admin.', 'success');
        
        // Close modal and reset form
        closeModal();
        resetForm();
        
        // Reset button state
        submitBtn.disabled = false;
        submitText.textContent = 'Submit Receipt';
        loadingSpinner.classList.add('hidden');
    }, 2000);
}

// Close modal when clicking outside
document.getElementById('paymentProofModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeModal();
    }
});

// Handle escape key to close modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && !document.getElementById('paymentProofModal').classList.contains('hidden')) {
        closeModal();
    }
});

// Form validation enhancement
document.getElementById('reference').addEventListener('input', function() {
    const value = this.value.trim();
    if (value.length > 0) {
        this.classList.add('border-green-300');
        this.classList.remove('border-red-300');
    }
});

// File input validation
document.getElementById('receipt').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        // Additional client-side validation
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!allowedTypes.includes(file.type)) {
            showNotification('Please upload only image files (JPG, PNG)', 'error');
            this.value = '';
            return;
        }

        if (file.size > maxSize) {
            showNotification('File size must be less than 10MB', 'error');
            this.value = '';
            return;
        }
        
        showNotification('Image uploaded successfully!', 'success');
    }
});

// Auto-focus on reference number field when page loads
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('reference').focus();
});

// Format currency display
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    }).format(amount);
}

// Utility function to show notifications
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full max-w-sm`;
    
    // Set notification style based on type
    switch(type) {
        case 'success':
            notification.className += ' bg-green-500 text-white';
            break;
        case 'error':
            notification.className += ' bg-red-500 text-white';
            break;
        case 'warning':
            notification.className += ' bg-yellow-500 text-white';
            break;
        default:
            notification.className += ' bg-blue-500 text-white';
    }
    
    // Add appropriate icon based on type
    let icon = '';
    switch(type) {
        case 'success':
            icon = '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            break;
        case 'error':
            icon = '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            break;
        case 'warning':
            icon = '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
            break;
        default:
            icon = '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    }
    
    notification.innerHTML = `
        <div class="flex items-start">
            ${icon}
            <span class="flex-1">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 1000);
}