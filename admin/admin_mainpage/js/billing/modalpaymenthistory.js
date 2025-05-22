
      // Payment Modal
        const paymentModal = document.getElementById('paymentModal');
        const openPaymentModalBtn = document.getElementById('openPaymentModal');
        const closePaymentModalBtn = document.getElementById('closePaymentModal');

        openPaymentModalBtn.addEventListener('click', () => {
            paymentModal.classList.remove('hidden');
        });

        closePaymentModalBtn.addEventListener('click', () => {
            paymentModal.classList.add('hidden');
        });

        // Edit Payment Modal
        const editPaymentModal = document.getElementById('editPaymentModal');
        const closeEditPaymentModalBtn = document.getElementById('closeEditPaymentModal');
        const editPaymentBtns = document.querySelectorAll('.edit-payment');

        editPaymentBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('edit_payment_id').value = btn.getAttribute('data-id');
                document.getElementById('edit_payment_type').value = btn.getAttribute('data-type');
                document.getElementById('edit_amount').value = btn.getAttribute('data-amount');
                document.getElementById('edit_payment_date').value = btn.getAttribute('data-date');
                document.getElementById('edit_payment_method').value = btn.getAttribute('data-method');
                document.getElementById('edit_description').value = btn.getAttribute('data-description');

                editPaymentModal.classList.remove('hidden');
            });
        });

        closeEditPaymentModalBtn.addEventListener('click', () => {
            editPaymentModal.classList.add('hidden');
        });

        // Delete Payment Modal
        const deletePaymentModal = document.getElementById('deletePaymentModal');
        const closeDeletePaymentModalBtn = document.getElementById('closeDeletePaymentModal');
        const deletePaymentBtns = document.querySelectorAll('.delete-payment');

        deletePaymentBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const paymentId = btn.getAttribute('data-id');
                const paymentAmount = parseFloat(btn.getAttribute('data-amount'));
                
                document.getElementById('delete_payment_id').value = paymentId;
                document.getElementById('delete_amount').textContent = '₱' + paymentAmount.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                deletePaymentModal.classList.remove('hidden');
            });
        });

        closeDeletePaymentModalBtn.addEventListener('click', () => {
            deletePaymentModal.classList.add('hidden');
        });

        // Edit Project Cost Modal
        const projectCostModal = document.getElementById('editProjectCostModal');
        const openProjectCostModalBtn = document.getElementById('editProjectCostBtn');
        const closeProjectCostModalBtn = document.getElementById('closeProjectCostModal');

        openProjectCostModalBtn.addEventListener('click', () => {
            projectCostModal.classList.remove('hidden');
        });

        closeProjectCostModalBtn.addEventListener('click', () => {
            projectCostModal.classList.add('hidden');
        });

        // Close modals when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === paymentModal) {
                paymentModal.classList.add('hidden');
            }
            if (e.target === editPaymentModal) {
                editPaymentModal.classList.add('hidden');
            }
            if (e.target === deletePaymentModal) {
                deletePaymentModal.classList.add('hidden');
            }
            if (e.target === projectCostModal) {
                projectCostModal.classList.add('hidden');
            }
        });