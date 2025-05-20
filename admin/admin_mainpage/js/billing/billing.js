  document.addEventListener('DOMContentLoaded', function() {
    
            
            // Payment Modal Controls
            const paymentModal = document.getElementById('paymentModal');
            const openPaymentModalBtn = document.getElementById('openPaymentModal');
            const closePaymentModalBtn = document.getElementById('closePaymentModal');
            const cancelPaymentBtn = document.getElementById('cancelPayment');
            
            openPaymentModalBtn.addEventListener('click', function() {
                paymentModal.classList.remove('hidden');
                setTimeout(() => {
                    paymentModal.classList.add('opacity-100');
                }, 10);
            });
            
            function closePaymentModal() {
                paymentModal.classList.add('hidden');
            }
            
            closePaymentModalBtn.addEventListener('click', closePaymentModal);
            cancelPaymentBtn.addEventListener('click', closePaymentModal);
            
            // Close modal when clicking outside
            paymentModal.addEventListener('click', function(event) {
                if (event.target === paymentModal) {
                    closePaymentModal();
                }
            });
            
            // Notification function
            window.showNotification = function(message, type = 'info') {
                const notification = document.getElementById('notification');
                const notificationMessage = document.getElementById('notificationMessage');
                
                notificationMessage.textContent = message;
                
                // Set border color based on notification type
                if (type === 'error') {
                    notification.classList.remove('border-primary-500');
                    notification.classList.add('border-red-500');
                    document.querySelector('#notification .fas').classList.remove('text-primary-500');
                    document.querySelector('#notification .fas').classList.add('text-red-500');
                } else {
                    notification.classList.remove('border-red-500');
                    notification.classList.add('border-primary-500');
                    document.querySelector('#notification .fas').classList.remove('text-red-500');
                    document.querySelector('#notification .fas').classList.add('text-primary-500');
                }
                
                notification.classList.remove('hidden');
                
                // Auto hide after 5 seconds
                setTimeout(() => {
                    closeNotification();
                }, 5000);
            };
            
            window.closeNotification = function() {
                const notification = document.getElementById('notification');
                notification.classList.add('hidden');
            };
            
         
        });