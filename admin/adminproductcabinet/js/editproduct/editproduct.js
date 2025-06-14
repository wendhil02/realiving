
// Global variables - declare them properly
let successTimeout;
let errorTimeout;
let currentStep = 1;
let totalSteps = 3;
let typeCounter = 1;

// Function to manually hide success message
function hideSuccessMessage() {
  const successDiv = document.getElementById('success-message');
  if (successDiv) {
    successDiv.classList.add('fade-out');
    
    setTimeout(() => {
      successDiv.classList.add('hidden');
      successDiv.classList.remove('fade-out');
    }, 500);
    
    if (successTimeout) {
      clearTimeout(successTimeout);
      successTimeout = null;
    }
  }
}

// Function to manually hide error message
function hideErrorMessage() {
  const errorDiv = document.getElementById('error-message');
  if (errorDiv) {
    errorDiv.classList.add('hidden');
    
    if (errorTimeout) {
      clearTimeout(errorTimeout);
      errorTimeout = null;
    }
  }
}

// Auto-hide success message after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
  const successDiv = document.getElementById('success-message');
  const errorDiv = document.getElementById('error-message');
  
  if (successDiv && !successDiv.classList.contains('hidden')) {
    successTimeout = setTimeout(() => {
      hideSuccessMessage();
    }, 5000);
  }
  
  if (errorDiv && !errorDiv.classList.contains('hidden')) {
    errorTimeout = setTimeout(() => {
      hideErrorMessage();
    }, 8000);
  }
});

// Image preview for main product image
document.addEventListener('DOMContentLoaded', function() {
  const mainImageInput = document.getElementById('main_image');
  if (mainImageInput) {
    mainImageInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      const preview = document.getElementById('main_image_preview');
      
      if (file && preview) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.innerHTML = `<img src="${e.target.result}" alt="Main Product Preview" class="max-w-xs max-h-48 rounded-lg border">`;
        };
        reader.readAsDataURL(file);
      } else if (preview) {
        preview.innerHTML = '';
      }
    });
  }
});

// Step navigation functions
function nextStep(step) {
  if (validateStep(step)) {
    hideStep(step);
    showStep(step + 1);
    updateStepIndicators(step + 1);
    currentStep = step + 1;
  }
}

function prevStep(step) {
  hideStep(step);
  showStep(step - 1);
  updateStepIndicators(step - 1);
  currentStep = step - 1;
}

function hideStep(step) {
  const stepDiv = document.getElementById(`step${step}`);
  if (stepDiv) {
    stepDiv.classList.remove('step-visible');
    stepDiv.classList.add('step-hidden');
  }
}

function showStep(step) {
  const stepDiv = document.getElementById(`step${step}`);
  if (stepDiv) {
    stepDiv.classList.remove('step-hidden');
    stepDiv.classList.add('step-visible');
    
    if (step === 3) {
      populateReviewContent();
    }
  }
}

function updateStepIndicators(activeStep) {
  for (let i = 1; i <= totalSteps; i++) {
    const indicator = document.getElementById(`step${i}-indicator`);
    const connector = document.getElementById(`connector${i}`);
    
    if (indicator) {
      indicator.classList.remove('active', 'completed');
      
      if (i < activeStep) {
        indicator.classList.add('completed');
      } else if (i === activeStep) {
        indicator.classList.add('active');
      }
    }
    
    if (connector) {
      connector.classList.remove('active', 'completed');
      
      if (i < activeStep) {
        connector.classList.add('completed');
      } else if (i === activeStep) {
        connector.classList.add('active');
      }
    }
  }
}

// Validation functions
function validateStep(step) {
  if (step === 1) {
    const productName = document.getElementById('product_name');
    const mainImage = document.getElementById('main_image');
    
    if (!productName || !productName.value.trim()) {
      alert('Please enter a product name');
      return false;
    }
    
    if (!mainImage || !mainImage.files[0]) {
      alert('Please select a main product image');
      return false;
    }
    
    return true;
  }
  
  if (step === 2) {
    const typeNames = document.querySelectorAll('input[name="type_names[]"]');
    let hasValidType = false;
    
    typeNames.forEach(input => {
      if (input.value.trim()) {
        hasValidType = true;
      }
    });
    
    if (!hasValidType) {
      alert('Please add at least one product type');
      return false;
    }
    
    return true;
  }
  
  return true;
}

// Product type management
function addProductType() {
  typeCounter++;
  const container = document.getElementById('types-container');
  
  if (!container) {
    console.error('Types container not found');
    return;
  }
  
  const typeHTML = `
    <div class="type-item bg-gray-50 p-6 rounded-lg mb-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold text-green-800">Type #${typeCounter}</h3>
        <button type="button" onclick="removeProductType(this)" class="text-red-600 hover:text-red-800">Remove Type</button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block mb-2 font-medium">Type Name <span class="text-red-600">*</span></label>
          <input type="text" name="type_names[]" required class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="e.g., T-Shirt, Hoodie, Mug" />
        </div>

        <div>
          <label class="block mb-2 font-medium">Base Price (₱) <span class="text-red-600">*</span></label>
          <input type="number" step="0.01" min="0" name="type_prices[]" required class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="0.00" />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block mb-2 font-medium">Type Image</label>
          <input type="file" name="type_images[]" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2" onchange="previewTypeImage(this)" />
          <div class="text-sm text-gray-500 mt-1">Max size: 5MB</div>
          <div class="type-image-preview mt-2"></div>
        </div>

        <div>
          <label class="block mb-2 font-medium">Type Description</label>
          <textarea name="type_descriptions[]" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Details about this type"></textarea>
        </div>
      </div>

      <!-- Sizes for this type -->
      <div class="border-t pt-4 mb-4">
        <div class="flex justify-between items-center mb-3">
          <h4 class="font-medium text-gray-700">Sizes for this Type</h4>
          <button type="button" onclick="addSizeToType(this)" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
            + Add Size
          </button>
        </div>

        <div class="sizes-for-type">
          <div class="size-item bg-white p-3 rounded border mb-3">
            <div class="flex justify-between items-center mb-2">
              <span class="font-medium text-sm">Size #1</span>
              <button type="button" onclick="removeSizeFromType(this)" class="text-red-500 text-sm">Remove</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
              <div>
                <label class="block mb-1 text-sm">Size Name *</label>
                <input type="text" name="sizes[${typeCounter-1}][0][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Small, Medium, Large..." />
              </div>

              <div>
                <label class="block mb-1 text-sm">Dimensions (e.g., 500x900mm)</label>
                <input type="text" name="sizes[${typeCounter-1}][0][dimensions]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="500x900mm" />
              </div>

              <div>
                <label class="block mb-1 text-sm">Extra Price (₱)</label>
                <input type="number" step="0.01" min="0" name="sizes[${typeCounter-1}][0][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Colors for this type -->
      <div class="border-t pt-4">
        <div class="flex justify-between items-center mb-3">
          <h4 class="font-medium text-gray-700">Colors for this Type</h4>
          <button type="button" onclick="addColorToType(this)" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
            + Add Color
          </button>
        </div>

        <div class="colors-for-type">
          <div class="color-item bg-white p-3 rounded border mb-3">
            <div class="flex justify-between items-center mb-2">
              <span class="font-medium text-sm">Color #1</span>
              <button type="button" onclick="removeColorFromType(this)" class="text-red-500 text-sm">Remove</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
              <div>
                <label class="block mb-1 text-sm">Color Name *</label>
                <input type="text" name="colors[${typeCounter-1}][0][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Red, Blue..." />
              </div>

              <div>
                <label class="block mb-1 text-sm">Color Code</label>
                <div class="flex gap-1">
                  <input type="color" class="w-8 h-8 border rounded cursor-pointer" onchange="updateColorCodeInType(this)" />
                  <input type="text" name="colors[${typeCounter-1}][0][code]" class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="#FF0000" />
                </div>
              </div>

              <div>
                <label class="block mb-1 text-sm">Extra Price (₱)</label>
                <input type="number" step="0.01" min="0" name="colors[${typeCounter-1}][0][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
              </div>

              <div>
                <label class="block mb-1 text-sm">Color Image</label>
                <input type="file" name="color_images[${typeCounter-1}][0]" accept="image/*" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" onchange="previewColorImageInType(this)" />
                <div class="text-xs text-gray-500">Max: 5MB</div>
                <div class="color-image-preview-small mt-1"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
  
  container.insertAdjacentHTML('beforeend', typeHTML);
}

function removeProductType(button) {
  const typeItem = button.closest('.type-item');
  if (typeItem) {
    typeItem.remove();
    // Update type numbers
    updateTypeNumbers();
  }
}

function updateTypeNumbers() {
  const typeItems = document.querySelectorAll('.type-item');
  typeItems.forEach((item, index) => {
    const title = item.querySelector('h3');
    if (title) {
      title.textContent = `Type #${index + 1}`;
    }
    
    // Update name attributes for sizes and colors
    updateSizeColorNames(item, index);
  });
}

function updateSizeColorNames(typeItem, typeIndex) {
  // Update size names
  const sizeItems = typeItem.querySelectorAll('.size-item');
  sizeItems.forEach((sizeItem, sizeIndex) => {
    const nameInput = sizeItem.querySelector('input[name*="[name]"]');
    const dimensionsInput = sizeItem.querySelector('input[name*="[dimensions]"]');
    const priceInput = sizeItem.querySelector('input[name*="[price]"]');
    
    if (nameInput) nameInput.setAttribute('name', `sizes[${typeIndex}][${sizeIndex}][name]`);
    if (dimensionsInput) dimensionsInput.setAttribute('name', `sizes[${typeIndex}][${sizeIndex}][dimensions]`);
    if (priceInput) priceInput.setAttribute('name', `sizes[${typeIndex}][${sizeIndex}][price]`);
  });
  
  // Update color names
  const colorItems = typeItem.querySelectorAll('.color-item');
  colorItems.forEach((colorItem, colorIndex) => {
    const nameInput = colorItem.querySelector('input[name*="colors["][name*="[name]"]');
    const codeInput = colorItem.querySelector('input[name*="colors["][name*="[code]"]');
    const priceInput = colorItem.querySelector('input[name*="colors["][name*="[price]"]');
    const fileInput = colorItem.querySelector('input[name*="color_images["]');
    
    if (nameInput) nameInput.setAttribute('name', `colors[${typeIndex}][${colorIndex}][name]`);
    if (codeInput) codeInput.setAttribute('name', `colors[${typeIndex}][${colorIndex}][code]`);
    if (priceInput) priceInput.setAttribute('name', `colors[${typeIndex}][${colorIndex}][price]`);
    if (fileInput) fileInput.setAttribute('name', `color_images[${typeIndex}][${colorIndex}]`);
  });
}

// Size management within types
function addSizeToType(button) {
  const typeItem = button.closest('.type-item');
  const sizesContainer = typeItem.querySelector('.sizes-for-type');
  const sizeCount = sizesContainer.querySelectorAll('.size-item').length;
  const typeIndex = Array.from(document.querySelectorAll('.type-item')).indexOf(typeItem);
  
  const sizeHTML = `
    <div class="size-item bg-white p-3 rounded border mb-3">
      <div class="flex justify-between items-center mb-2">
        <span class="font-medium text-sm">Size #${sizeCount + 1}</span>
        <button type="button" onclick="removeSizeFromType(this)" class="text-red-500 text-sm">Remove</button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div>
          <label class="block mb-1 text-sm">Size Name *</label>
          <input type="text" name="sizes[${typeIndex}][${sizeCount}][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Small, Medium, Large..." />
        </div>

        <div>
          <label class="block mb-1 text-sm">Dimensions (e.g., 500x900mm)</label>
          <input type="text" name="sizes[${typeIndex}][${sizeCount}][dimensions]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="500x900mm" />
        </div>

        <div>
          <label class="block mb-1 text-sm">Extra Price (₱)</label>
          <input type="number" step="0.01" min="0" name="sizes[${typeIndex}][${sizeCount}][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
        </div>
      </div>
    </div>
  `;
  
  sizesContainer.insertAdjacentHTML('beforeend', sizeHTML);
}

function removeSizeFromType(button) {
  const sizeItem = button.closest('.size-item');
  const sizesContainer = sizeItem.closest('.sizes-for-type');
  
  if (sizeItem) {
    sizeItem.remove();
    
    // Update size numbers
    const remainingSizes = sizesContainer.querySelectorAll('.size-item');
    remainingSizes.forEach((item, index) => {
      const title = item.querySelector('span');
      if (title) {
        title.textContent = `Size #${index + 1}`;
      }
    });
  }
}

// Color management within types
function addColorToType(button) {
  const typeItem = button.closest('.type-item');
  const colorsContainer = typeItem.querySelector('.colors-for-type');
  const colorCount = colorsContainer.querySelectorAll('.color-item').length;
  const typeIndex = Array.from(document.querySelectorAll('.type-item')).indexOf(typeItem);
  
  const colorHTML = `
    <div class="color-item bg-white p-3 rounded border mb-3">
      <div class="flex justify-between items-center mb-2">
        <span class="font-medium text-sm">Color #${colorCount + 1}</span>
        <button type="button" onclick="removeColorFromType(this)" class="text-red-500 text-sm">Remove</button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
          <label class="block mb-1 text-sm">Color Name *</label>
          <input type="text" name="colors[${typeIndex}][${colorCount}][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Red, Blue..." />
        </div>

        <div>
          <label class="block mb-1 text-sm">Color Code</label>
          <div class="flex gap-1">
            <input type="color" class="w-8 h-8 border rounded cursor-pointer" onchange="updateColorCodeInType(this)" />
            <input type="text" name="colors[${typeIndex}][${colorCount}][code]" class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="#FF0000" />
          </div>
        </div>

        <div>
          <label class="block mb-1 text-sm">Extra Price (₱)</label>
          <input type="number" step="0.01" min="0" name="colors[${typeIndex}][${colorCount}][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
        </div>

        <div>
          <label class="block mb-1 text-sm">Color Image</label>
          <input type="file" name="color_images[${typeIndex}][${colorCount}]" accept="image/*" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" onchange="previewColorImageInType(this)" />
          <div class="text-xs text-gray-500">Max: 5MB</div>
          <div class="color-image-preview-small mt-1"></div>
        </div>
      </div>
    </div>
  `;
  
  colorsContainer.insertAdjacentHTML('beforeend', colorHTML);
}

function removeColorFromType(button) {
  const colorItem = button.closest('.color-item');
  const colorsContainer = colorItem.closest('.colors-for-type');
  
  if (colorItem) {
    colorItem.remove();
    
    // Update color numbers
    const remainingColors = colorsContainer.querySelectorAll('.color-item');
    remainingColors.forEach((item, index) => {
      const title = item.querySelector('span');
      if (title) {
        title.textContent = `Color #${index + 1}`;
      }
    });
  }
}

// Image preview functions
function previewTypeImage(input) {
  const file = input.files[0];
  const preview = input.parentElement.querySelector('.type-image-preview');
  
  if (file && preview) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.innerHTML = `<img src="${e.target.result}" alt="Type Preview" class="max-w-32 max-h-24 rounded border">`;
    };
    reader.readAsDataURL(file);
  } else if (preview) {
    preview.innerHTML = '';
  }
}

function previewColorImageInType(input) {
  const file = input.files[0];
  const preview = input.parentElement.querySelector('.color-image-preview-small');
  
  if (file && preview) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.innerHTML = `<img src="${e.target.result}" alt="Color Preview" class="w-16 h-16 rounded border object-cover">`;
    };
    reader.readAsDataURL(file);
  } else if (preview) {
    preview.innerHTML = '';
  }
}

function updateColorCodeInType(colorInput) {
  const textInput = colorInput.parentElement.querySelector('input[type="text"]');
  if (textInput) {
    textInput.value = colorInput.value;
  }
}

// Review content population
function populateReviewContent() {
  const reviewDiv = document.getElementById('review-content');
  if (!reviewDiv) return;
  
  let html = '';
  
  // Main product info
  const productName = document.getElementById('product_name')?.value || '';
  const description = document.getElementById('description')?.value || '';
  const status = document.getElementById('status')?.value || '';
  const mainImageFile = document.getElementById('main_image')?.files[0];
  
  html += `
    <div class="border rounded-lg p-4 bg-blue-50">
      <h3 class="text-lg font-semibold mb-2 text-blue-800">Main Product Information</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <p><strong>Product Name:</strong> ${productName}</p>
          <p><strong>Status:</strong> ${status}</p>
          <p><strong>Description:</strong> ${description || 'No description provided'}</p>
        </div>
        <div>
          <p><strong>Main Image:</strong> ${mainImageFile ? mainImageFile.name : 'No image selected'}</p>
        </div>
      </div>
    </div>
  `;
  
  // Product types
  const typeItems = document.querySelectorAll('.type-item');
  html += '<div class="space-y-4">';
  
  typeItems.forEach((typeItem, index) => {
    const typeName = typeItem.querySelector('input[name="type_names[]"]')?.value || '';
    const typePrice = typeItem.querySelector('input[name="type_prices[]"]')?.value || '';
    const typeDescription = typeItem.querySelector('textarea[name="type_descriptions[]"]')?.value || '';
    
    if (typeName) {
      html += `
        <div class="border rounded-lg p-4 bg-green-50">
          <h4 class="text-lg font-semibold mb-2 text-green-800">Type: ${typeName}</h4>
          <p><strong>Base Price:</strong> ₱${typePrice}</p>
          <p><strong>Description:</strong> ${typeDescription || 'No description'}</p>
          
          <!-- Sizes -->
          <div class="mt-3">
            <strong>Sizes:</strong>
            <ul class="list-disc list-inside ml-4">
      `;
      
      const sizeItems = typeItem.querySelectorAll('.size-item');
      sizeItems.forEach(sizeItem => {
        const sizeName = sizeItem.querySelector('input[name*="[name]"]')?.value || '';
        const sizeDimensions = sizeItem.querySelector('input[name*="[dimensions]"]')?.value || '';
        const sizePrice = sizeItem.querySelector('input[name*="[price]"]')?.value || '';
        
        if (sizeName) {
          html += `<li>${sizeName}${sizeDimensions ? ` (${sizeDimensions})` : ''}${sizePrice ? ` +₱${sizePrice}` : ''}</li>`;
        }
      });
      
      html += `
            </ul>
          </div>
          
          <!-- Colors -->
          <div class="mt-3">
            <strong>Colors:</strong>
            <ul class="list-disc list-inside ml-4">
      `;
      
      const colorItems = typeItem.querySelectorAll('.color-item');
      colorItems.forEach(colorItem => {
        const colorName = colorItem.querySelector('input[name*="[name]"]')?.value || '';
        const colorCode = colorItem.querySelector('input[name*="[code]"]')?.value || '';
        const colorPrice = colorItem.querySelector('input[name*="[price]"]')?.value || '';
        
        if (colorName) {
          html += `<li>${colorName}${colorCode ? ` (${colorCode})` : ''}${colorPrice ? ` +₱${colorPrice}` : ''}</li>`;
        }
      });
      
      html += `
            </ul>
          </div>
        </div>
      `;
    }
  });
  
  html += '</div>';
  reviewDiv.innerHTML = html;
}

