function editPayment(payment) {
    // Populate the edit form with payment data
    document.getElementById('editPaymentId').value = payment.id;
    document.getElementById('editPaymentType').value = payment.payment_type;
    document.getElementById('editPaymentAmount').value = payment.amount;
    document.getElementById('editPaymentDate').value = payment.payment_date;
    document.getElementById('editPaymentMethod').value = payment.payment_method;
    document.getElementById('editPaymentDescription').value = payment.description || '';
    
    // Show the edit modal
    document.getElementById('editPaymentModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function deletePayment(paymentId, paymentType, amount) {
    // Set the payment ID for deletion
    document.getElementById('deletePaymentId').value = paymentId;
    
    // Update the details shown in the confirmation
    document.getElementById('deletePaymentDetails').innerHTML = `
        <p class="font-medium text-gray-800">${paymentType}</p>
        <p class="text-lg font-bold text-gray-900">₱${amount}</p>
    `;
    
    // Show the delete modal
    document.getElementById('deletePaymentModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Modal control functions
document.addEventListener('DOMContentLoaded', function() {
    // Edit modal controls
    const editPaymentModal = document.getElementById('editPaymentModal');
    const closeEditPaymentModalBtn = document.getElementById('closeEditPaymentModal');
    const cancelEditPaymentBtn = document.getElementById('cancelEditPayment');

    function closeEditPaymentModal() {
        editPaymentModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('editPaymentForm').reset();
    }

    if (closeEditPaymentModalBtn) {
        closeEditPaymentModalBtn.addEventListener('click', closeEditPaymentModal);
    }

    if (cancelEditPaymentBtn) {
        cancelEditPaymentBtn.addEventListener('click', closeEditPaymentModal);
    }

    editPaymentModal.addEventListener('click', function(e) {
        if (e.target === editPaymentModal) {
            closeEditPaymentModal();
        }
    });

    // Delete modal controls
    const deletePaymentModal = document.getElementById('deletePaymentModal');
    const closeDeletePaymentModalBtn = document.getElementById('closeDeletePaymentModal');
    const cancelDeletePaymentBtn = document.getElementById('cancelDeletePayment');

    function closeDeletePaymentModal() {
        deletePaymentModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    if (closeDeletePaymentModalBtn) {
        closeDeletePaymentModalBtn.addEventListener('click', closeDeletePaymentModal);
    }

    if (cancelDeletePaymentBtn) {
        cancelDeletePaymentBtn.addEventListener('click', closeDeletePaymentModal);
    }

    deletePaymentModal.addEventListener('click', function(e) {
        if (e.target === deletePaymentModal) {
            closeDeletePaymentModal();
        }
    });
});