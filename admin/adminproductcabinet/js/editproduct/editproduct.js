
    
    let typeCounter = 1;
    let colorCounters = [1];
    let sizeCounters = [1];

    // Function to add size to a specific type
    function addSizeToType(button) {
      const typeItem = button.closest('.type-item');
      const sizesContainer = typeItem.querySelector('.sizes-for-type');
      const typeIndex = Array.from(document.querySelectorAll('.type-item')).indexOf(typeItem);
      
      if (!sizeCounters[typeIndex]) {
        sizeCounters[typeIndex] = 1;
      }
      
      const sizeIndex = sizeCounters[typeIndex];
      
      const sizeHtml = `
        <div class="size-item bg-white p-3 rounded border mb-3">
          <div class="flex justify-between items-center mb-2">
            <span class="font-medium text-sm">Size #${sizeIndex + 1}</span>
            <button type="button" onclick="removeSizeFromType(this)" class="text-red-500 text-sm">Remove</button>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
              <label class="block mb-1 text-sm">Size Name *</label>
              <input type="text" name="sizes[${typeIndex}][${sizeIndex}][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Small, Medium, Large..." />
            </div>
            <div>
              <label class="block mb-1 text-sm">Dimensions (e.g., 500x900mm)</label>
              <input type="text" name="sizes[${typeIndex}][${sizeIndex}][dimensions]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="500x900mm" />
            </div>
            <div>
              <label class="block mb-1 text-sm">Extra Price (₱)</label>
              <input type="number" step="0.01" min="0" name="sizes[${typeIndex}][${sizeIndex}][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
            </div>
          </div>
        </div>
      `;
      
      sizesContainer.insertAdjacentHTML('beforeend', sizeHtml);
      sizeCounters[typeIndex]++;
    }

    // Function to remove size from type
    function removeSizeFromType(button) {
      const sizeItem = button.closest('.size-item');
      const sizesContainer = sizeItem.closest('.sizes-for-type');
      
      if (sizesContainer.children.length > 1) {
        sizeItem.remove();
      } else {
        alert('At least one size is required for each type.');
      }
    }

    // Function to add color to a specific type
    function addColorToType(button) {
      const typeItem = button.closest('.type-item');
      const colorsContainer = typeItem.querySelector('.colors-for-type');
      const typeIndex = Array.from(document.querySelectorAll('.type-item')).indexOf(typeItem);
      
      if (!colorCounters[typeIndex]) {
        colorCounters[typeIndex] = 1;
      }
      
      const colorIndex = colorCounters[typeIndex];
      
      const colorHtml = `
        <div class="color-item bg-white p-3 rounded border mb-3">
          <div class="flex justify-between items-center mb-2">
            <span class="font-medium text-sm">Color #${colorIndex + 1}</span>
            <button type="button" onclick="removeColorFromType(this)" class="text-red-500 text-sm">Remove</button>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
              <label class="block mb-1 text-sm">Color Name *</label>
              <input type="text" name="colors[${typeIndex}][${colorIndex}][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Red, Blue..." />
            </div>
            <div>
              <label class="block mb-1 text-sm">Color Code</label>
              <div class="flex gap-1">
                <input type="color" class="w-8 h-8 border rounded cursor-pointer" onchange="updateColorCodeInType(this)" />
                <input type="text" name="colors[${typeIndex}][${colorIndex}][code]" class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="#FF0000" />
              </div>
            </div>
            <div>
              <label class="block mb-1 text-sm">Extra Price (₱)</label>
              <input type="number" step="0.01" min="0" name="colors[${typeIndex}][${colorIndex}][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
            </div>
            <div>
              <label class="block mb-1 text-sm">Color Image</label>
              <input type="file" name="color_images[${typeIndex}][${colorIndex}]" accept="image/*" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" onchange="previewColorImageInType(this)" />
              <div class="color-image-preview-small mt-1"></div>
            </div>
          </div>
        </div>
      `;
      
      colorsContainer.insertAdjacentHTML('beforeend', colorHtml);
      colorCounters[typeIndex]++;
    }

    // Function to remove color from type
    function removeColorFromType(button) {
      const colorItem = button.closest('.color-item');
      const colorsContainer = colorItem.closest('.colors-for-type');
      
      if (colorsContainer.children.length > 1) {
        colorItem.remove();
      } else {
        alert('At least one color is required for each type.');
      }
    }

   // Function to add new product type
    function addProductType() {
      const typesContainer = document.getElementById('types-container');
      
      const typeHtml = `
        <div class="type-item bg-gray-50 p-6 rounded-lg mb-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-green-800">Type #${typeCounter + 1}</h3>
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
                    <input type="text" name="sizes[${typeCounter}][0][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Small, Medium, Large..." />
                  </div>
                  <div>
                    <label class="block mb-1 text-sm">Dimensions (e.g., 500x900mm)</label>
                    <input type="text" name="sizes[${typeCounter}][0][dimensions]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="500x900mm" />
                  </div>
                  <div>
                    <label class="block mb-1 text-sm">Extra Price (₱)</label>
                    <input type="number" step="0.01" min="0" name="sizes[${typeCounter}][0][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
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
                    <input type="text" name="colors[${typeCounter}][0][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Red, Blue..." />
                  </div>
                  <div>
                    <label class="block mb-1 text-sm">Color Code</label>
                    <div class="flex gap-1">
                      <input type="color" class="w-8 h-8 border rounded cursor-pointer" onchange="updateColorCodeInType(this)" />
                      <input type="text" name="colors[${typeCounter}][0][code]" class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="#FF0000" />
                    </div>
                  </div>
                  <div>
                    <label class="block mb-1 text-sm">Extra Price (₱)</label>
                    <input type="number" step="0.01" min="0" name="colors[${typeCounter}][0][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
                  </div>
                  <div>
                    <label class="block mb-1 text-sm">Color Image</label>
                    <input type="file" name="color_images[${typeCounter}][0]" accept="image/*" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" onchange="previewColorImageInType(this)" />
                    <div class="color-image-preview-small mt-1"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
      
      typesContainer.insertAdjacentHTML('beforeend', typeHtml);
      
      // Initialize counters for new type
      colorCounters[typeCounter] = 1;
      sizeCounters[typeCounter] = 1;
      typeCounter++;
    }

    // Function to remove product type
    function removeProductType(button) {
      const typeItem = button.closest('.type-item');
      const typesContainer = document.getElementById('types-container');
      
      if (typesContainer.children.length > 1) {
        typeItem.remove();
      } else {
        alert('At least one product type is required.');
      }
    }

    // Function to update color code from color picker
    function updateColorCodeInType(colorPicker) {
      const colorCodeInput = colorPicker.nextElementSibling;
      colorCodeInput.value = colorPicker.value.toUpperCase();
    }

    // Function to preview main image
    document.getElementById('main_image').addEventListener('change', function(e) {
      const file = e.target.files[0];
      const preview = document.getElementById('main_image_preview');
      
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.innerHTML = `<img src="${e.target.result}" alt="Main Image Preview" class="w-32 h-32 object-cover rounded-lg border">`;
        };
        reader.readAsDataURL(file);
      } else {
        preview.innerHTML = '';
      }
    });

    // Function to preview type image
    function previewTypeImage(input) {
      const file = input.files[0];
      const preview = input.parentNode.querySelector('.type-image-preview');
      
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.innerHTML = `<img src="${e.target.result}" alt="Type Image Preview" class="w-24 h-24 object-cover rounded border">`;
        };
        reader.readAsDataURL(file);
      } else {
        preview.innerHTML = '';
      }
    }

    // Function to preview color image
    function previewColorImageInType(input) {
      const file = input.files[0];
      const preview = input.parentNode.querySelector('.color-image-preview-small');
      
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.innerHTML = `<img src="${e.target.result}" alt="Color Preview" class="w-16 h-16 object-cover rounded border">`;
        };
        reader.readAsDataURL(file);
      } else {
        preview.innerHTML = '';
      }
    }

    // Step navigation functions
    function nextStep(currentStep) {
      // Validate current step
      if (!validateStep(currentStep)) {
        return;
      }

      // Hide current step
      document.getElementById(`step${currentStep}`).classList.remove('step-visible');
      document.getElementById(`step${currentStep}`).classList.add('step-hidden');

      // Show next step
      const nextStepNum = currentStep + 1;
      document.getElementById(`step${nextStepNum}`).classList.remove('step-hidden');
      document.getElementById(`step${nextStepNum}`).classList.add('step-visible');

      // Update step indicators
      updateStepIndicators(nextStepNum);

      // If moving to review step, populate review content
      if (nextStepNum === 3) {
        populateReviewContent();
      }

      // Scroll to top
      window.scrollTo(0, 0);
    }

    function prevStep(currentStep) {
      // Hide current step
      document.getElementById(`step${currentStep}`).classList.remove('step-visible');
      document.getElementById(`step${currentStep}`).classList.add('step-hidden');

      // Show previous step
      const prevStepNum = currentStep - 1;
      document.getElementById(`step${prevStepNum}`).classList.remove('step-hidden');
      document.getElementById(`step${prevStepNum}`).classList.add('step-visible');

      // Update step indicators
      updateStepIndicators(prevStepNum);

      // Scroll to top
      window.scrollTo(0, 0);
    }

    function updateStepIndicators(activeStep) {
      // Reset all indicators
      for (let i = 1; i <= 3; i++) {
        const indicator = document.getElementById(`step${i}-indicator`);
        const connector = document.getElementById(`connector${i}`);
        
        indicator.classList.remove('active', 'completed');
        if (connector) {
          connector.classList.remove('active', 'completed');
        }
        
        if (i < activeStep) {
          indicator.classList.add('completed');
          if (connector) {
            connector.classList.add('completed');
          }
        } else if (i === activeStep) {
          indicator.classList.add('active');
        }
      }
    }

    function validateStep(step) {
      if (step === 1) {
        const productName = document.getElementById('product_name').value.trim();
        const mainImage = document.getElementById('main_image').files[0];
        
        if (!productName) {
          alert('Please enter a product name.');
          document.getElementById('product_name').focus();
          return false;
        }
        
        if (!mainImage) {
          alert('Please select a main product image.');
          document.getElementById('main_image').focus();
          return false;
        }
        
        return true;
      }
      
      if (step === 2) {
        const typeNames = document.querySelectorAll('input[name="type_names[]"]');
        const typePrices = document.querySelectorAll('input[name="type_prices[]"]');
        
        let valid = true;
        
        typeNames.forEach((nameInput, index) => {
          if (!nameInput.value.trim()) {
            alert(`Please enter a name for Type #${index + 1}.`);
            nameInput.focus();
            valid = false;
            return;
          }
        });
        
        if (!valid) return false;
        
        typePrices.forEach((priceInput, index) => {
          if (!priceInput.value || parseFloat(priceInput.value) < 0) {
            alert(`Please enter a valid price for Type #${index + 1}.`);
            priceInput.focus();
            valid = false;
            return;
          }
        });
        
        return valid;
      }
      
      return true;
    }

    function populateReviewContent() {
      const reviewContent = document.getElementById('review-content');
      
      // Get main product info
      const productName = document.getElementById('product_name').value;
      const description = document.getElementById('description').value;
      const status = document.getElementById('status').value;
      const mainImage = document.getElementById('main_image').files[0];
      
      let reviewHtml = `
        <div class="bg-blue-50 p-6 rounded-lg">
          <h3 class="text-xl font-semibold mb-4 text-blue-800">Main Product Information</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p><strong>Product Name:</strong> ${productName}</p>
              <p><strong>Status:</strong> ${status}</p>
              <p><strong>Description:</strong> ${description || 'No description provided'}</p>
            </div>
            <div>
              ${mainImage ? `<p><strong>Main Image:</strong> ${mainImage.name}</p>` : ''}
            </div>
          </div>
        </div>
      `;
      
      // Get types info
      const typeItems = document.querySelectorAll('.type-item');
      
      typeItems.forEach((typeItem, typeIndex) => {
        const typeName = typeItem.querySelector('input[name="type_names[]"]').value;
        const typePrice = typeItem.querySelector('input[name="type_prices[]"]').value;
        const typeDesc = typeItem.querySelector('textarea[name="type_descriptions[]"]').value;
        const typeImageInput = typeItem.querySelector('input[name="type_images[]"]');
        const typeImage = typeImageInput ? typeImageInput.files[0] : null;
        
        reviewHtml += `
          <div class="bg-green-50 p-6 rounded-lg">
            <h3 class="text-xl font-semibold mb-4 text-green-800">Type #${typeIndex + 1}: ${typeName}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <p><strong>Base Price:</strong> ₱${typePrice}</p>
                <p><strong>Description:</strong> ${typeDesc || 'No description'}</p>
                ${typeImage ? `<p><strong>Image:</strong> ${typeImage.name}</p>` : ''}
              </div>
            </div>
        `;
        
        // Get sizes for this type
        const sizeItems = typeItem.querySelectorAll('.size-item');
        if (sizeItems.length > 0) {
          reviewHtml += `<h4 class="font-semibold mb-2">Sizes:</h4><ul class="list-disc list-inside mb-4">`;
          sizeItems.forEach((sizeItem) => {
            const sizeName = sizeItem.querySelector('input[name*="[name]"]').value;
            const sizeDimensions = sizeItem.querySelector('input[name*="[dimensions]"]').value;
            const sizePrice = sizeItem.querySelector('input[name*="[price]"]').value;
            
            reviewHtml += `<li>${sizeName}${sizeDimensions ? ` (${sizeDimensions})` : ''} - Extra: ₱${sizePrice || '0.00'}</li>`;
          });
          reviewHtml += `</ul>`;
        }
        
        // Get colors for this type
        const colorItems = typeItem.querySelectorAll('.color-item');
        if (colorItems.length > 0) {
          reviewHtml += `<h4 class="font-semibold mb-2">Colors:</h4><ul class="list-disc list-inside">`;
          colorItems.forEach((colorItem) => {
            const colorName = colorItem.querySelector('input[name*="[name]"]').value;
            const colorCode = colorItem.querySelector('input[name*="[code]"]').value;
            const colorPrice = colorItem.querySelector('input[name*="[price]"]').value;
            
            reviewHtml += `<li>${colorName}${colorCode ? ` (${colorCode})` : ''} - Extra: ₱${colorPrice || '0.00'}</li>`;
          });
          reviewHtml += `</ul>`;
        }
        
        reviewHtml += `</div>`;
      });
      
      reviewContent.innerHTML = reviewHtml;
    }

    // Form submission validation
    document.getElementById('product-form').addEventListener('submit', function(e) {
      if (!validateStep(1) || !validateStep(2)) {
        e.preventDefault();
        alert('Please complete all required fields before submitting.');
      }
    });

    // Initialize step indicators
    updateStepIndicators(1);
