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

// Reset form function (for complete reset)
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
                showNotification(`Receipt submitted successfully! Total submissions: ${data.submission_count}. You can submit more receipts for this billing code.`, 'success');
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
    
    // Show a helpful message
    showNotification("Ready for next receipt! Upload another image to submit again.", "info");
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

// ✅ FIXED: Actual submission instead of simulation
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

    // ✅ ACTUAL SUBMISSION TO PHP (not simulation)
    fetch('submit_billing.php', {
        method: 'POST',
        body: currentFormData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`Receipt submitted successfully! Total submissions: ${data.submission_count}. You can submit more receipts for this reference number.`, 'success');
            
            // Close modal
            closeModal();
            
            // ✅ ONLY RESET THE RECEIPT IMAGE (keep billing details intact)
            resetReceiptOnly();
            
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
        loadingSpinner.classList.add('hidden');
    });
}

// Variables to track tab switching and user interaction
let isTabVisible = true;
let userHasInteracted = false;
let lastInputTime = 0;

// Track tab visibility
document.addEventListener('visibilitychange', function() {
    isTabVisible = !document.hidden;
});

// Track user interaction with inputs
function trackUserInteraction() {
    userHasInteracted = true;
    lastInputTime = Date.now();
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('paymentProofModal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal();
            }
        });
    }
});

// Handle escape key to close modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && !document.getElementById('paymentProofModal').classList.contains('hidden')) {
        closeModal();
    }
});

// ✅ FIXED: Enhanced form validation without unwanted notifications
document.addEventListener('DOMContentLoaded', function() {
    const referenceInput = document.getElementById('reference');
    if (referenceInput) {
        // Track actual user typing (not just focus events)
        referenceInput.addEventListener('input', trackUserInteraction);
        
        // Only show visual feedback, no notifications on input changes
        referenceInput.addEventListener('input', function() {
            const value = this.value.trim();
            if (value.length > 0) {
                this.classList.add('border-green-300');
                this.classList.remove('border-red-300');
            } else {
                this.classList.remove('border-green-300');
                this.classList.add('border-red-300');
            }
        });

        // Remove unwanted focus event listeners that might cause notifications
        referenceInput.addEventListener('focus', function() {
            // Only track interaction, don't show notifications
            trackUserInteraction();
        });
    }
});

// ✅ FIXED: File input validation without tab-switching notifications
document.addEventListener('DOMContentLoaded', function() {
    const receiptInput = document.getElementById('receipt');
    if (receiptInput) {
        receiptInput.addEventListener('change', function(event) {
            // Only process if this is a real user interaction, not a tab switch
            if (!isTabVisible || !userHasInteracted) {
                return;
            }

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
                
                // Only show success notification for actual file uploads
                if (event.isTrusted) {
                    showNotification('Image uploaded successfully!', 'success');
                }
            }
        });
    }
});

// Auto-focus on reference number field when page loads
document.addEventListener('DOMContentLoaded', function() {
    const referenceInput = document.getElementById('reference');
    if (referenceInput) {
        // Small delay to ensure page is fully loaded
        setTimeout(() => {
            referenceInput.focus();
        }, 100);
    }
});

// Format currency display
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    }).format(amount);
}

// ✅ ENHANCED: Notification system with better timing and no duplicates
let activeNotifications = new Set();

function showNotification(message, type = 'info') {
    // Prevent duplicate notifications
    const notificationKey = `${message}-${type}`;
    if (activeNotifications.has(notificationKey)) {
        return;
    }
    
    // Don't show notifications if tab is not visible (prevents tab-switch notifications)
    if (!isTabVisible) {
        return;
    }
    
    activeNotifications.add(notificationKey);
    
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
            <button onclick="this.parentElement.parentElement.remove(); activeNotifications.delete('${notificationKey}')" class="ml-4 text-white hover:text-gray-200 flex-shrink-0">
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
    
    // Auto-remove after 4 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                    activeNotifications.delete(notificationKey);
                }
            }, 300);
        }
    }, 4000);
}

// adminbilling.js - Complete admin billing functionality with loading states

class AdminBilling {
    constructor() {
        this.currentReferenceNumber = '';
        this.billingData = [];
        this.filteredData = [];
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadBillingRecords();
    }

    setupEventListeners() {
        // Confirm button event listener
        const confirmBtn = document.getElementById('confirmReceiveBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', () => {
                if (this.currentReferenceNumber) {
                    this.markAsReceived(this.currentReferenceNumber);
                }
            });
        }

        // Search and filter event listeners
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const dateFilter = document.getElementById('dateFilter');

        if (searchInput) {
            searchInput.addEventListener('input', () => this.applyFilters());
        }
        if (statusFilter) {
            statusFilter.addEventListener('change', () => this.applyFilters());
        }
        if (dateFilter) {
            dateFilter.addEventListener('change', () => this.applyFilters());
        }

        // Modal event listeners
        this.setupModalEventListeners();
    }

    setupModalEventListeners() {
        // Close modal when clicking outside
        const confirmModal = document.getElementById('confirmModal');
        const receiptModal = document.getElementById('receiptModal');

        if (confirmModal) {
            confirmModal.addEventListener('click', (e) => {
                if (e.target === confirmModal) {
                    this.closeModal('confirmModal');
                }
            });
        }

        if (receiptModal) {
            receiptModal.addEventListener('click', (e) => {
                if (e.target === receiptModal) {
                    this.closeModal('receiptModal');
                }
            });
        }

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal('confirmModal');
                this.closeModal('receiptModal');
            }
        });
    }

    // Show loading state on button
    showButtonLoading(buttonId) {
        const button = document.getElementById(buttonId);
        if (button) {
            button.classList.add('btn-loading');
            button.disabled = true;
            
            // Also disable other modal buttons
            const cancelBtn = document.getElementById('cancelBtn');
            const closeBtn = document.getElementById('closeModalBtn');
            if (cancelBtn) cancelBtn.disabled = true;
            if (closeBtn) closeBtn.disabled = true;
        }
    }

    // Hide loading state on button
    hideButtonLoading(buttonId) {
        const button = document.getElementById(buttonId);
        if (button) {
            button.classList.remove('btn-loading');
            button.disabled = false;
            
            // Re-enable other modal buttons
            const cancelBtn = document.getElementById('cancelBtn');
            const closeBtn = document.getElementById('closeModalBtn');
            if (cancelBtn) cancelBtn.disabled = false;
            if (closeBtn) closeBtn.disabled = false;
        }
    }

    // Show notification
    showNotification(message, type = 'success') {
        const container = document.getElementById('notificationContainer');
        if (!container) return;

        const notification = document.createElement('div');
        
        const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
        const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        
        notification.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 transform translate-x-full transition-transform duration-300 mb-2`;
        notification.innerHTML = `
            <i class="${icon}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    }

    // Mark billing as received
    async markAsReceived(referenceNumber) {
        try {
            console.log('Starting markAsReceived for:', referenceNumber);
            
            // Show loading state
            this.showButtonLoading('confirmReceiveBtn');
            
            const formData = new FormData();
            formData.append('reference_number', referenceNumber);
            
            console.log('Sending request to mark_received.php');
            
            const response = await fetch('mark_received.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response:', text);
                throw new Error('Server returned non-JSON response');
            }
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (data.success) {
                this.showNotification('Payment marked as received and notification sent successfully!', 'success');
                this.closeModal('confirmModal');
                
                // Update the billing record in the current data
                this.updateBillingStatus(referenceNumber, 'received');
                
                // Refresh the display
                this.renderBillingRecords();
                
            } else {
                this.showNotification(data.message || 'Failed to mark as received', 'error');
                console.error('Server error:', data);
            }
            
        } catch (error) {
            console.error('Error in markAsReceived:', error);
            this.showNotification('An error occurred while processing the request: ' + error.message, 'error');
        } finally {
            // Always hide loading state
            this.hideButtonLoading('confirmReceiveBtn');
        }
    }

    // Update billing status in local data
    updateBillingStatus(referenceNumber, status) {
        const billingIndex = this.billingData.findIndex(b => b.reference_number === referenceNumber);
        if (billingIndex !== -1) {
            this.billingData[billingIndex].status = status;
            this.billingData[billingIndex].received_date = new Date().toISOString();
        }
    }

    // Show confirmation modal
    showConfirmModal(referenceNumber, clientName) {
        this.currentReferenceNumber = referenceNumber;
        
        const confirmClientName = document.getElementById('confirmClientName');
        const confirmRefNumber = document.getElementById('confirmRefNumber');
        
        if (confirmClientName) confirmClientName.textContent = clientName || 'Unknown Client';
        if (confirmRefNumber) confirmRefNumber.textContent = referenceNumber;
        
        const modal = document.getElementById('confirmModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    // Close modal
    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
        
        // Reset loading state if closing confirm modal
        if (modalId === 'confirmModal') {
            this.hideButtonLoading('confirmReceiveBtn');
            this.currentReferenceNumber = '';
        }
    }

    // Show receipt modal
    showReceiptModal(receiptPath) {
        const receiptImage = document.getElementById('receiptImage');
        const modal = document.getElementById('receiptModal');
        
        if (receiptImage && modal) {
            receiptImage.src = receiptPath;
            modal.classList.remove('hidden');
        }
    }

    // Load billing records from server
    async loadBillingRecords() {
        try {
            this.showLoadingState();
            
            const response = await fetch('get_billing_records.php');
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.billingData = data.data || [];
                this.filteredData = [...this.billingData];
                this.renderBillingRecords();
            } else {
                this.showNotification(data.message || 'Failed to load billing records', 'error');
                this.showEmptyState('Failed to load billing records');
            }
            
        } catch (error) {
            console.error('Error loading billing records:', error);
            this.showNotification('Error loading billing records: ' + error.message, 'error');
            this.showEmptyState('Error loading billing records');
        }
    }

    // Show loading state
    showLoadingState() {
        const container = document.getElementById('billingContainer');
        if (container) {
            container.innerHTML = `
                <div class="col-span-full flex flex-col items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                    <p class="mt-4 text-gray-600">Loading billing records...</p>
                </div>
            `;
        }
    }

    // Show empty state
    showEmptyState(message = 'No billing records found') {
        const container = document.getElementById('billingContainer');
        if (container) {
            container.innerHTML = `
                <div class="col-span-full flex flex-col items-center justify-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-inbox text-6xl"></i>
                    </div>
                    <p class="text-gray-600">${message}</p>
                </div>
            `;
        }
    }

    // Render billing records
    renderBillingRecords() {
        const container = document.getElementById('billingContainer');
        if (!container) return;

        if (this.filteredData.length === 0) {
            this.showEmptyState();
            return;
        }

        container.innerHTML = this.filteredData.map(billing => this.createBillingCard(billing)).join('');
    }

    // Create billing card HTML
    createBillingCard(billing) {
        const statusClass = billing.status === 'received' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
        const statusIcon = billing.status === 'received' ? 'fas fa-check-circle' : 'fas fa-clock';
        
        return `
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">${billing.client_name || 'Unknown Client'}</h3>
                        <p class="text-sm text-gray-600">Ref: ${billing.reference_number}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium ${statusClass}">
                        <i class="${statusIcon} mr-1"></i>
                        ${billing.status.charAt(0).toUpperCase() + billing.status.slice(1)}
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Project Cost:</span>
                        <span class="font-medium">₱${parseFloat(billing.total_project_cost || 0).toLocaleString()}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Remaining Balance:</span>
                        <span class="font-medium">₱${parseFloat(billing.remaining_balance || 0).toLocaleString()}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Submitted:</span>
                        <span class="text-sm">${new Date(billing.submission_date).toLocaleDateString()}</span>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    ${billing.receipt_path ? `
                        <button onclick="adminBilling.showReceiptModal('${billing.receipt_path}')" 
                                class="flex-1 bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <i class="fas fa-eye mr-1"></i>View Receipt
                        </button>
                    ` : ''}
                    
                    ${billing.status === 'pending' ? `
                        <button onclick="adminBilling.showConfirmModal('${billing.reference_number}', '${billing.client_name || 'Unknown Client'}')" 
                                class="flex-1 bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                            <i class="fas fa-check mr-1"></i>Mark Received
                        </button>
                    ` : ''}
                </div>
            </div>
        `;
    }

    // Apply filters
    applyFilters() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const dateFilter = document.getElementById('dateFilter');

        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const statusValue = statusFilter ? statusFilter.value : '';
        const dateValue = dateFilter ? dateFilter.value : '';

        this.filteredData = this.billingData.filter(billing => {
            const matchesSearch = !searchTerm || 
                billing.reference_number.toLowerCase().includes(searchTerm) ||
                (billing.client_name && billing.client_name.toLowerCase().includes(searchTerm));
            
            const matchesStatus = !statusValue || billing.status === statusValue;
            
            const matchesDate = !dateValue || 
                new Date(billing.submission_date).toDateString() === new Date(dateValue).toDateString();

            return matchesSearch && matchesStatus && matchesDate;
        });

        this.renderBillingRecords();
    }

    // Refresh billing records
    refreshBillings() {
        this.loadBillingRecords();
    }
}

// Initialize admin billing when DOM is ready
let adminBilling;

document.addEventListener('DOMContentLoaded', function() {
    adminBilling = new AdminBilling();
});

// Global functions for backward compatibility
function refreshBillings() {
    if (adminBilling) {
        adminBilling.refreshBillings();
    }
}

function applyFilters() {
    if (adminBilling) {
        adminBilling.applyFilters();
    }
}

function closeModal(modalId) {
    if (adminBilling) {
        adminBilling.closeModal(modalId);
    }
}