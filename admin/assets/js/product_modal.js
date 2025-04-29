function handleCardClick(e, id, image, name, description, size, price, supplier, contact, serial) {
    if (e.target.closest('a')) return;
    openModal(id, image, name, description, size, price, supplier, contact, serial);
}

function openModal(id, image, name, description, size, price, supplier, contact, serial) {
    console.log('Modal values:', { id, image, name, description, size, price, supplier, contact, serial });

    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('modalContent');
    const body = document.body;

    const numericPrice = typeof price === 'string'
        ? parseFloat(price.replace(/[^0-9.-]/g, ''))
        : Number(price);

    const formattedPrice = isNaN(numericPrice) ? '₱0.00' : `₱${numericPrice.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })}`;

    document.getElementById('modalImage').src = image || 'default.png';
    document.getElementById('modalName').textContent = name || 'No Name Available';
    document.getElementById('modalDescription').textContent = description || 'No Description Available';
    document.getElementById('modalSize').textContent = size || '-';
    document.getElementById('modalPrice').textContent = formattedPrice;
    document.getElementById('modalSupplier').textContent = supplier || '-';
    document.getElementById('modalContact').textContent = contact || '-';
    document.getElementById('modalSerial').textContent = serial || '-';

    modal.classList.remove('hidden');
    body.classList.add('overflow-hidden');

    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0', 'translate-y-4');
        modalContent.classList.add('scale-100', 'opacity-100', 'translate-y-0');
    }, 50);

    document.addEventListener('keydown', escHandler);
}

function closeModal() {
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('modalContent');
    const body = document.body;

    modalContent.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
    modalContent.classList.add('scale-95', 'opacity-0', 'translate-y-4');

    setTimeout(() => {
        modal.classList.add('hidden');
        body.classList.remove('overflow-hidden');
        document.removeEventListener('keydown', escHandler);
    }, 200);
}

function escHandler(e) {
    if (e.key === 'Escape') closeModal();
}

document.addEventListener('mousedown', (e) => {
    const modal = document.getElementById('productModal');
    const content = document.getElementById('modalContent');

    if (!modal.classList.contains('hidden') && !content.contains(e.target)) {
        closeModal();
    }
});
