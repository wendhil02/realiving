    let allBillings = [];
        let currentBilling = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadBillings();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Search input
            document.getElementById('searchInput').addEventListener('input', debounce(applyFilters, 300));
            
            // Confirm receive button
            document.getElementById('confirmReceiveBtn').addEventListener('click', function() {
                if (currentBilling) {
                    markAsReceived(currentBilling);
                    closeModal('confirmModal');
                }
            });
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        async function loadBillings() {
            try {
                const response = await fetch('fetch_billings.php');
                const data = await response.json();
                
                if (data.success) {
                    allBillings = data.billings;
                    displayBillings(allBillings);
                } else {
                    showNotification('Error loading billings: ' + data.message, 'error');
                }
            } catch (error) {
                showNotification('Error: ' + error.message, 'error');
            }
        }

        function displayBillings(billings) {
            const container = document.getElementById('billingContainer');
            
            if (billings.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full flex flex-col items-center justify-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
                        <h4 class="text-xl text-gray-600">No billing records found</h4>
                    </div>
                `;
                return;
            }

            container.innerHTML = billings.map(billing => `
                <div class="bg-white rounded-lg shadow-lg card-hover relative overflow-hidden">
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full ${billing.status === 'received' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                            ${billing.status === 'received' ? 'Received' : 'Pending'}
                        </span>
                    </div>
                    
                    <div class="p-6">
                        <h5 class="text-xl font-bold text-blue-600 mb-4">
                            <i class="fas fa-receipt mr-2"></i>${billing.reference_number}
                        </h5>
                        
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <div class="space-y-2 text-sm">
                                    <p><strong>Client:</strong> ${billing.client_name}</p>
                                    <p><strong>Email:</strong> ${billing.email || 'Not available'}</p>
                                    <p><strong>Total Cost:</strong> ₱${parseFloat(billing.total_project_cost || 0).toLocaleString()}</p>
                                    <p><strong>Balance:</strong> ₱${parseFloat(billing.remaining_balance || 0).toLocaleString()}</p>
                                    <p><strong>Date:</strong> ${formatDate(billing.submission_date)}</p>
                                </div>
                            </div>
                            ${billing.receipt_image ? `
                            <div class="ml-4">
                                <img src="../../realivingpage/page/billingpage/uploads/receipts/${billing.receipt_image}" 
                                     alt="Receipt" 
                                     class="w-20 h-20 object-cover rounded-lg cursor-pointer receipt-preview"
                                     onclick="showReceiptModal('../../realivingpage/page/billingpage/uploads/receipts/${billing.receipt_image}')">
                            </div>
                            ` : ''}
                        </div>
                        
                        <div class="space-y-2">
                            ${billing.status !== 'received' ? `
                            <button onclick="confirmReceive('${billing.reference_number}', '${billing.client_name}')" 
                                    class="w-full gradient-button text-white py-2 px-4 rounded-lg font-semibold hover:scale-105 transition-all">
                                <i class="fas fa-check mr-2"></i>Mark as Received
                            </button>
                            ` : `
                            <button disabled class="w-full bg-gray-100 text-gray-500 py-2 px-4 rounded-lg font-semibold cursor-not-allowed">
                                <i class="fas fa-check-circle mr-2"></i>Already Received
                            </button>
                            `}
                            
                            <button onclick="sendReminder('${billing.reference_number}')" 
                                    class="w-full bg-blue-50 text-blue-600 border border-blue-200 py-2 px-4 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fas fa-envelope mr-2"></i>Send Reminder
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;

            let filtered = allBillings.filter(billing => {
                const matchesSearch = billing.reference_number.toLowerCase().includes(searchTerm) ||
                                    billing.client_name.toLowerCase().includes(searchTerm);
                
                const matchesStatus = !statusFilter || billing.status === statusFilter;
                
                const matchesDate = !dateFilter || 
                                  billing.submission_date.startsWith(dateFilter);

                return matchesSearch && matchesStatus && matchesDate;
            });

            displayBillings(filtered);
        }

        function confirmReceive(referenceNumber, clientName) {
            currentBilling = referenceNumber;
            document.getElementById('confirmClientName').textContent = clientName;
            document.getElementById('confirmRefNumber').textContent = referenceNumber;
            
            showModal('confirmModal');
        }

        async function markAsReceived(referenceNumber) {
            try {
                showLoading(true);
                
                const response = await fetch('mark_received.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `reference_number=${encodeURIComponent(referenceNumber)}`
                });

                const data = await response.json();
                
                if (data.success) {
                    showNotification('Payment marked as received and notification sent!', 'success');
                    loadBillings(); // Refresh the list
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            } catch (error) {
                showNotification('Error: ' + error.message, 'error');
            } finally {
                showLoading(false);
            }
        }

        async function sendReminder(referenceNumber) {
            try {
                showLoading(true);
                
                const response = await fetch('send_reminder.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `reference_number=${encodeURIComponent(referenceNumber)}`
                });

                const data = await response.json();
                
                if (data.success) {
                    showNotification('Reminder sent successfully!', 'success');
                } else {
                    showNotification('Error sending reminder: ' + data.message, 'error');
                }
            } catch (error) {
                showNotification('Error: ' + error.message, 'error');
            } finally {
                showLoading(false);
            }
        }

        function showReceiptModal(imagePath) {
            document.getElementById('receiptImage').src = imagePath;
            showModal('receiptModal');
        }

        function showModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function refreshBillings() {
            loadBillings();
            showNotification('Billing records refreshed!', 'info');
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function showLoading(show) {
            // You can implement loading states here if needed
            // For now, we'll just log it
            console.log('Loading:', show);
        }

        function showNotification(message, type = 'info') {
            const typeClasses = {
                'success': 'bg-green-100 border-green-500 text-green-700',
                'error': 'bg-red-100 border-red-500 text-red-700',
                'warning': 'bg-yellow-100 border-yellow-500 text-yellow-700',
                'info': 'bg-blue-100 border-blue-500 text-blue-700'
            };

            const notification = document.createElement('div');
            notification.className = `border-l-4 p-4 rounded shadow-lg min-w-80 ${typeClasses[type] || typeClasses.info}`;
            notification.innerHTML = `
                <div class="flex justify-between items-center">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.getElementById('notificationContainer').appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            const modals = ['receiptModal', 'confirmModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    closeModal(modalId);
                }
            });
        });