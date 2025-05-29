const { typeColors, typeSizes, hasColors, hasSizes, product } = window.ProductConfig;

let selectedType = null;
let selectedSize = null;
let selectedColor = null;
let selectedTypePrice = 0;
let selectedSizePrice = 0;
let selectedColorPrice = 0;

function selectType(typeId, typeName, typeImage, price) {
    selectedType = { id: typeId, name: typeName, image: typeImage, price: price };
    selectedTypePrice = parseFloat(price) || 0;
    
    // Update hidden field
    const typeIdField = document.getElementById('selected_type_id');
    if (typeIdField) typeIdField.value = typeId;
    
    // Update main image with proper path handling
    updateMainImage(typeImage, typeName);
    
    // Update selection summary
    updateTypeSummary(typeName);
    
    // Clear dependent selections
    clearSizeSelection();
    clearColorSelection();
    
    // Update visual selection state
    updateTypeSelection(typeId);
    
    // Show next appropriate step
    showNextStep(typeId);
    
    updateTotalPrice();
}

function updateMainImage(imagePath, variantName) {
    const mainImage = document.getElementById('main-image');
    const selectedVariantName = document.getElementById('selected-variant-name');
    
    if (mainImage && selectedVariantName) {
        if (imagePath && imagePath.trim() !== '') {
            mainImage.src = '../../' + imagePath;
        } else {
            mainImage.src = '../../' + product.main_image;
        }
        selectedVariantName.textContent = variantName;
    }
}

function updateTypeSummary(typeName) {
    const typeSummary = document.getElementById('selected-type-summary');
    if (typeSummary) {
        typeSummary.textContent = typeName;
    }
}

function clearSizeSelection() {
    selectedSize = null;
    selectedSizePrice = 0;
    
    const sizeIdField = document.getElementById('selected_size_id');
    if (sizeIdField) sizeIdField.value = '';
    
    const sizeSummary = document.getElementById('selected-size-summary');
    if (sizeSummary) sizeSummary.textContent = 'Please select a size';
}

function clearColorSelection() {
    selectedColor = null;
    selectedColorPrice = 0;
    
    const colorIdField = document.getElementById('selected_color_id');
    if (colorIdField) colorIdField.value = '';
    
    const colorSummary = document.getElementById('selected-color-summary');
    if (colorSummary) colorSummary.textContent = 'Please select a color';
}

function updateTypeSelection(typeId) {
    // Remove selection from all type cards
    document.querySelectorAll('.type-card').forEach(card => {
        card.classList.remove('selected');
        const indicator = card.querySelector('.selection-indicator');
        if (indicator) indicator.classList.add('hidden');
    });
    
    // Add selection to current card
    const selectedCard = document.querySelector(`[data-type-id="${typeId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('selected');
        const indicator = selectedCard.querySelector('.selection-indicator');
        if (indicator) indicator.classList.remove('hidden');
    }
}

function showNextStep(typeId) {
    if (hasSizes && typeSizes[typeId] && typeSizes[typeId].length > 0) {
        showSizesForType(typeId);
        hideColorSelection();
        hideQuantityAndActions();
    } else if (hasColors && typeColors[typeId] && typeColors[typeId].length > 0) {
        showColorsForType(typeId);
        hideQuantityAndActions();
    } else {
        showQuantityAndActions();
    }
}

function selectSize(sizeId, sizeName, dimensions, price) {
    selectedSize = { id: sizeId, name: sizeName, dimensions: dimensions, price: price };
    selectedSizePrice = parseFloat(price) || 0;
    
    // Update hidden field
    const sizeIdField = document.getElementById('selected_size_id');
    if (sizeIdField) sizeIdField.value = sizeId;
    
    // Update selection summary
    updateSizeSummary(sizeName, dimensions);
    
    // Update variant name
    updateVariantName();
    
    // Update visual selection
    updateSizeSelection(sizeId);
    
    // Show next step
    if (hasColors && typeColors[selectedType.id] && typeColors[selectedType.id].length > 0) {
        showColorsForType(selectedType.id);
        hideQuantityAndActions();
    } else {
        showQuantityAndActions();
    }
    
    updateTotalPrice();
}

function updateSizeSummary(sizeName, dimensions) {
    const sizeSummary = document.getElementById('selected-size-summary');
    if (sizeSummary) {
        let sizeText = sizeName;
        if (dimensions) sizeText += ` (${dimensions})`;
        sizeSummary.textContent = sizeText;
    }
}

function updateVariantName() {
    const selectedVariantName = document.getElementById('selected-variant-name');
    if (selectedVariantName && selectedType) {
        let variantName = selectedType.name;
        if (selectedSize) variantName += ` - ${selectedSize.name}`;
        if (selectedColor) variantName += ` - ${selectedColor.name}`;
        selectedVariantName.textContent = variantName;
    }
}

function updateSizeSelection(sizeId) {
    document.querySelectorAll('.size-card').forEach(card => {
        card.classList.remove('selected');
        const indicator = card.querySelector('.selection-indicator');
        if (indicator) indicator.classList.add('hidden');
    });
    
    const selectedCard = document.querySelector(`[data-size-id="${sizeId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('selected');
        const indicator = selectedCard.querySelector('.selection-indicator');
        if (indicator) indicator.classList.remove('hidden');
    }
}

function selectColor(colorId, colorName, colorCode, colorImage, price) {
    selectedColor = { id: colorId, name: colorName, code: colorCode, image: colorImage, price: price };
    selectedColorPrice = parseFloat(price) || 0;
    
    // Update hidden field
    const colorIdField = document.getElementById('selected_color_id');
    if (colorIdField) colorIdField.value = colorId;
    
    // Update main image with color-specific image if available
    updateMainImageForColor(colorImage);
    
    // Update variant name
    updateVariantName();
    
    // Update selection summary
    updateColorSummary(colorName);
    
    // Update visual selection
    updateColorSelection(colorId);
    
    // Show final step
    showQuantityAndActions();
    
    updateTotalPrice();
}

function updateMainImageForColor(colorImage) {
    const mainImage = document.getElementById('main-image');
    if (mainImage) {
        if (colorImage && colorImage.trim() !== '') {
            mainImage.src = '../../' + colorImage;
        } else if (selectedType.image && selectedType.image.trim() !== '') {
            mainImage.src = '../../' + selectedType.image;
        } else {
            mainImage.src = '../../' + product.main_image;
        }
    }
}

function updateColorSummary(colorName) {
    const colorSummary = document.getElementById('selected-color-summary');
    if (colorSummary) colorSummary.textContent = colorName;
}

function updateColorSelection(colorId) {
    document.querySelectorAll('.color-card').forEach(card => {
        card.classList.remove('selected');
        const indicator = card.querySelector('.selection-indicator');
        if (indicator) indicator.classList.add('hidden');
    });
    
    const selectedCard = document.querySelector(`[data-color-id="${colorId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('selected');
        const indicator = selectedCard.querySelector('.selection-indicator');
        if (indicator) indicator.classList.remove('hidden');
    }
}

// ===== IMPROVED HELPER FUNCTIONS =====

function showSizesForType(typeId) {
    if (!hasSizes) return;
    
    const sizeSelection = document.getElementById('size-selection');
    const sizesContainer = document.getElementById('sizes-container');
    
    if (!sizeSelection || !sizesContainer) return;
    
    // Clear existing sizes
    sizesContainer.innerHTML = '';
    
    // Check if this type has sizes
    if (typeSizes[typeId] && typeSizes[typeId].length > 0) {
        sizeSelection.classList.remove('hidden');
        
        typeSizes[typeId].forEach(size => {
            const sizeCard = createSizeCard(size);
            sizesContainer.appendChild(sizeCard);
        });
        
        // Smooth scroll to size selection
        setTimeout(() => smoothScrollToElement('size-selection'), 300);
    } else {
        // No sizes available, auto-proceed
        sizeSelection.classList.add('hidden');
        updateSizeSummary('No size options', null);
        
        if (hasColors) {
            showColorsForType(typeId);
        } else {
            showQuantityAndActions();
        }
    }
}

function createSizeCard(size) {
    const sizeCard = document.createElement('div');
    sizeCard.className = 'size-card border-2 border-gray-200 rounded-lg p-4 hover:shadow-md cursor-pointer transition-all';
    sizeCard.setAttribute('data-size-id', size.size_id);
    sizeCard.setAttribute('tabindex', '0');
    sizeCard.setAttribute('role', 'button');
    sizeCard.onclick = () => selectSize(size.size_id, size.size_name, size.dimensions, size.price);
    
    // Add keyboard support
    sizeCard.onkeydown = (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            selectSize(size.size_id, size.size_name, size.dimensions, size.price);
        }
    };
    
    sizeCard.innerHTML = `
        <div class="text-center">
            <h4 class="font-semibold text-gray-800 mb-1">${escapeHtml(size.size_name)}</h4>
            ${size.dimensions ? `<p class="text-sm text-gray-600 mb-2">${escapeHtml(size.dimensions)}</p>` : ''}
            ${parseFloat(size.price) > 0 ? 
                `<p class="text-sm text-green-600">+₱${parseFloat(size.price).toFixed(2)}</p>` : 
                '<p class="text-sm text-gray-500">No extra cost</p>'}
        </div>
        <div class="hidden selection-indicator mt-2 text-center">
            <i class="fas fa-check-circle text-indigo-600"></i>
        </div>
    `;
    
    return sizeCard;
}

function showColorsForType(typeId) {
    if (!hasColors) return;
    
    const colorSelection = document.getElementById('color-selection');
    const colorsContainer = document.getElementById('colors-container');
    
    if (!colorSelection || !colorsContainer) return;
    
    // Clear existing colors
    colorsContainer.innerHTML = '';
    
    // Check if this type has colors
    if (typeColors[typeId] && typeColors[typeId].length > 0) {
        colorSelection.classList.remove('hidden');
        
        typeColors[typeId].forEach(color => {
            const colorCard = createColorCard(color);
            colorsContainer.appendChild(colorCard);
        });
        
        // Smooth scroll to color selection
        setTimeout(() => smoothScrollToElement('color-selection'), 300);
    } else {
        // No colors available, auto-proceed
        colorSelection.classList.add('hidden');
        updateColorSummary('No color options');
        showQuantityAndActions();
    }
}

function createColorCard(color) {
    const colorCard = document.createElement('div');
    colorCard.className = 'color-card border-2 border-gray-200 rounded-lg p-3 hover:shadow-md text-center cursor-pointer transition-all';
    colorCard.setAttribute('data-color-id', color.color_id);
    colorCard.setAttribute('tabindex', '0');
    colorCard.setAttribute('role', 'button');
    colorCard.onclick = () => selectColor(color.color_id, color.color_name, color.color_code, color.color_image, color.price);
    
    // Add keyboard support
    colorCard.onkeydown = (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            selectColor(color.color_id, color.color_name, color.color_code, color.color_image, color.price);
        }
    };
    
    colorCard.innerHTML = `
        ${color.color_image ? `<img src="../../${escapeHtml(color.color_image)}" alt="${escapeHtml(color.color_name)}" class="w-full h-16 object-cover rounded mb-2" />` : ''}
        <div class="flex items-center justify-center mb-2">
            <div class="w-4 h-4 rounded-full mr-2 border border-gray-300" style="background-color: ${escapeHtml(color.color_code || '#ccc')}"></div>
            <span class="text-sm font-medium">${escapeHtml(color.color_name)}</span>
        </div>
        ${parseFloat(color.price) > 0 ? `<p class="text-xs text-green-600">+₱${parseFloat(color.price).toFixed(2)}</p>` : ''}
        <div class="hidden selection-indicator mt-2">
            <i class="fas fa-check-circle text-indigo-600"></i>
        </div>
    `;
    
    return colorCard;
}

// ===== UTILITY FUNCTIONS =====

function escapeHtml(unsafe) {
    if (typeof unsafe !== 'string') return unsafe;
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function hideColorSelection() {
    const colorSelection = document.getElementById('color-selection');
    if (colorSelection) colorSelection.classList.add('hidden');
}

function showQuantityAndActions() {
    const quantityOptions = document.getElementById('quantity-options');
    const actionButtons = document.getElementById('action-buttons');
    
    if (quantityOptions) quantityOptions.classList.remove('hidden');
    if (actionButtons) actionButtons.classList.remove('hidden');
    
    // Smooth scroll to quantity section
    setTimeout(() => smoothScrollToElement('quantity-options'), 300);
}

function hideQuantityAndActions() {
    const quantityOptions = document.getElementById('quantity-options');
    const actionButtons = document.getElementById('action-buttons');
    
    if (quantityOptions) quantityOptions.classList.add('hidden');
    if (actionButtons) actionButtons.classList.add('hidden');
}

function changeQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    if (!quantityInput) return;
    
    let currentQuantity = parseInt(quantityInput.value) || 1;
    let newQuantity = Math.max(1, currentQuantity + change);
    
    quantityInput.value = newQuantity;
    
    const quantitySummary = document.getElementById('quantity-summary');
    if (quantitySummary) quantitySummary.textContent = newQuantity;
    
    updateTotalPrice();
}

function updateTotalPrice() {
    const unitPrice = selectedTypePrice + selectedSizePrice + selectedColorPrice;
    const quantity = parseInt(document.getElementById('quantity')?.value) || 1;
    const totalPrice = unitPrice * quantity;
    
    // Update price displays
    const selectedPriceEl = document.getElementById('selected-price');
    const totalPriceEl = document.getElementById('total-price');
    
    if (selectedPriceEl) selectedPriceEl.textContent = `₱${unitPrice.toFixed(2)}`;
    if (totalPriceEl) totalPriceEl.textContent = `₱${totalPrice.toFixed(2)}`;
}

function smoothScrollToElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'nearest' 
        });
    }
}

// ===== EVENT LISTENERS =====

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('customization-form');
    if (form) {
        form.addEventListener('submit', validateFormSubmission);
    }
    
    // Initialize price display
    updateTotalPrice();
    
    // Add loading states to submit buttons
    document.querySelectorAll('button[type="submit"]').forEach(button => {
        button.addEventListener('click', function() {
            addLoadingState(this);
        });
    });
});

function validateFormSubmission(e) {
    if (!selectedType) {
        e.preventDefault();
        showAlert('Please select a product type first.', 'warning');
        return false;
    }
    
    // Check if size is required and not selected
    if (hasSizes && typeSizes[selectedType.id] && typeSizes[selectedType.id].length > 0 && !selectedSize) {
        e.preventDefault();
        showAlert('Please select a size for this product type.', 'warning');
        return false;
    }
    
    // Check if color is required and not selected
    if (hasColors && typeColors[selectedType.id] && typeColors[selectedType.id].length > 0 && !selectedColor) {
        e.preventDefault();
        showAlert('Please select a color for this product type.', 'warning');
        return false;
    }
    
    return true;
}

function showAlert(message, type = 'info') {
    // Create a simple alert - you can replace this with a more sophisticated notification system
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 ${
        type === 'warning' ? 'bg-yellow-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    alertDiv.textContent = message;
    
    document.body.appendChild(alertDiv);
    
    // Remove after 3 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 3000);
}

function addLoadingState(button) {
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    
    // Remove loading state after 5 seconds (fallback)
    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    }, 5000);
}