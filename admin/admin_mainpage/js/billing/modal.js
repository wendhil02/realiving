
    document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const paymentModal = document.getElementById('paymentModal');
            const editProjectCostModal = document.getElementById('editProjectCostModal');
            const openPaymentModalBtn = document.getElementById('openPaymentModal');
            const closePaymentModalBtn = document.getElementById('closePaymentModal');
            const cancelPaymentBtn = document.getElementById('cancelPayment');
            const editProjectCostBtn = document.getElementById('editProjectCostBtn');
            const closeProjectCostModalBtn = document.getElementById('closeProjectCostModal');
            const cancelProjectCostEditBtn = document.getElementById('cancelProjectCostEdit');

            // Payment modal controls
            if (openPaymentModalBtn) {
                openPaymentModalBtn.addEventListener('click', function() {
                    paymentModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }

            function closePaymentModal() {
                paymentModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                document.getElementById('paymentForm').reset();
            }

            if (closePaymentModalBtn) {
                closePaymentModalBtn.addEventListener('click', closePaymentModal);
            }

            if (cancelPaymentBtn) {
                cancelPaymentBtn.addEventListener('click', closePaymentModal);
            }

            // Project cost modal controls
            if (editProjectCostBtn) {
                editProjectCostBtn.addEventListener('click', function() {
                    editProjectCostModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }

            function closeProjectCostModal() {
                editProjectCostModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            if (closeProjectCostModalBtn) {
                closeProjectCostModalBtn.addEventListener('click', closeProjectCostModal);
            }

            if (cancelProjectCostEditBtn) {
                cancelProjectCostEditBtn.addEventListener('click', closeProjectCostModal);
            }

            // Close modals when clicking outside
            paymentModal.addEventListener('click', function(e) {
                if (e.target === paymentModal) {
                    closePaymentModal();
                }
            });

            editProjectCostModal.addEventListener('click', function(e) {
                if (e.target === editProjectCostModal) {
                    closeProjectCostModal();
                }
            });

            // Auto-hide success/error messages
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');

            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.opacity = '0';
                    setTimeout(function() {
                        successMessage.remove();
                    }, 300);
                }, 3000);
            }

            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.style.opacity = '0';
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 300);
                }, 5000);
            }

        
        });