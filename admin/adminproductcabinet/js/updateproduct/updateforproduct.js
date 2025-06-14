// Initialize variables
let typeIndex = 0;
let sizeCounters = {};
let colorCounters = {};

// Function to initialize the script with data from PHP
function initializeProductForm(productTypes = []) {
  typeIndex = productTypes.length;

  // Initialize counters for existing types
  productTypes.forEach((type, index) => {
    sizeCounters[index] = type.sizes ? type.sizes.length : 0;
    colorCounters[index] = type.colors ? type.colors.length : 0;
  });
}

// Add new type
document.getElementById("addType").addEventListener("click", function () {
  const container = document.getElementById("typesContainer");
  sizeCounters[typeIndex] = 0;
  colorCounters[typeIndex] = 0;

  const typeHtml = `
        <div class="type-section" data-type-index="${typeIndex}">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Type Name</label>
                    <input type="text" name="type_names[${typeIndex}]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Base Price</label>
                    <input type="number" step="0.01" name="type_prices[${typeIndex}]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type Image</label>
                    <input type="file" name="type_images[${typeIndex}]" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Type Description</label>
                    <textarea name="type_descriptions[${typeIndex}]" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="mb-3">
                <h6><i class="fas fa-ruler"></i> Sizes</h6>
                <div class="sizes-container" data-type-index="${typeIndex}"></div>
                <button type="button" class="btn btn-secondary btn-sm add-size" data-type-index="${typeIndex}">
                    <i class="fas fa-plus"></i> Add Size
                </button>
            </div>
            <div class="mb-3">
                <h6><i class="fas fa-palette"></i> Colors</h6>
                <div class="colors-container" data-type-index="${typeIndex}"></div>
                <button type="button" class="btn btn-secondary btn-sm add-color" data-type-index="${typeIndex}">
                    <i class="fas fa-plus"></i> Add Color
                </button>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-type">
                <i class="fas fa-trash"></i> Remove Type
            </button>
        </div>
    `;
  container.insertAdjacentHTML("beforeend", typeHtml);
  typeIndex++;
});

// Event delegation for dynamic elements
document.addEventListener("click", function (e) {
  // Remove type
  if (
    e.target.classList.contains("remove-type") ||
    e.target.parentElement.classList.contains("remove-type")
  ) {
    e.target.closest(".type-section").remove();
  }

  // Add size
  if (
    e.target.classList.contains("add-size") ||
    e.target.parentElement.classList.contains("add-size")
  ) {
    const btn = e.target.classList.contains("add-size")
      ? e.target
      : e.target.parentElement;
    const typeIdx = btn.getAttribute("data-type-index");
    const container = document.querySelector(
      `.sizes-container[data-type-index="${typeIdx}"]`
    );
    const sizeIdx = sizeCounters[typeIdx] || 0;

    const sizeHtml = `
            <div class="size-item">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="sizes[${typeIdx}][${sizeIdx}][name]" class="form-control" placeholder="Size Name">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="sizes[${typeIdx}][${sizeIdx}][dimensions]" class="form-control" placeholder="Dimensions">
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="sizes[${typeIdx}][${sizeIdx}][price]" class="form-control" placeholder="Additional Price">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-remove remove-size">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    container.insertAdjacentHTML("beforeend", sizeHtml);
    sizeCounters[typeIdx] = sizeIdx + 1;
  }

  // Remove size
  if (
    e.target.classList.contains("remove-size") ||
    e.target.parentElement.classList.contains("remove-size")
  ) {
    e.target.closest(".size-item").remove();
  }

  // Add color
  if (
    e.target.classList.contains("add-color") ||
    e.target.parentElement.classList.contains("add-color")
  ) {
    const btn = e.target.classList.contains("add-color")
      ? e.target
      : e.target.parentElement;
    const typeIdx = btn.getAttribute("data-type-index");
    const container = document.querySelector(
      `.colors-container[data-type-index="${typeIdx}"]`
    );
    const colorIdx = colorCounters[typeIdx] || 0;

    const colorHtml = `
            <div class="color-item">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="colors[${typeIdx}][${colorIdx}][name]" class="form-control" placeholder="Color Name">
                    </div>
                    <div class="col-md-2">
                        <input type="color" name="colors[${typeIdx}][${colorIdx}][code]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <input type="file" name="color_images[${typeIdx}][${colorIdx}]" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="colors[${typeIdx}][${colorIdx}][price]" class="form-control" placeholder="Add'l Price">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-remove remove-color">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    container.insertAdjacentHTML("beforeend", colorHtml);
    colorCounters[typeIdx] = colorIdx + 1;
  }

  // Remove color
  if (
    e.target.classList.contains("remove-color") ||
    e.target.parentElement.classList.contains("remove-color")
  ) {
    e.target.closest(".color-item").remove();
  }
});

// Form validation
document.getElementById("productForm").addEventListener("submit", function (e) {
  const productName = document
    .querySelector('input[name="product_name"]')
    .value.trim();
  if (!productName) {
    e.preventDefault();
    alert("Product name is required!");
    return false;
  }

  // Validate that at least one type has a name if types exist
  const typeNames = document.querySelectorAll('input[name^="type_names"]');
  let hasValidType = false;

  typeNames.forEach(function (input) {
    if (input.value.trim()) {
      hasValidType = true;
    }
  });

  if (typeNames.length > 0 && !hasValidType) {
    e.preventDefault();
    alert("At least one product type must have a name!");
    return false;
  }
});

// Auto-dismiss alerts after 5 seconds
document.addEventListener("DOMContentLoaded", function () {
  setTimeout(function () {
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(function (alert) {
      if (typeof bootstrap !== "undefined" && bootstrap.Alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      }
    });
  }, 5000);
});

// Add this to your JavaScript file (updateforproduct.js) or in a script tag

// Update the add color function to handle existing color IDs properly
function addColorToType(typeIndex) {
  const colorsContainer = document.querySelector(
    `.colors-container[data-type-index="${typeIndex}"]`
  );
  const existingColors = colorsContainer.querySelectorAll(".color-item");
  const colorIndex = existingColors.length;

  const colorHTML = `
        <div class="color-item">
            <!-- No existing_color_id for new colors -->
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="colors[${typeIndex}][${colorIndex}][name]"
                        class="form-control" placeholder="Color Name">
                </div>
                <div class="col-md-2">
                    <input type="color" name="colors[${typeIndex}][${colorIndex}][code]"
                        class="form-control" value="#000000">
                </div>
                <div class="col-md-3">
                    <input type="file" name="color_images[${typeIndex}][${colorIndex}]"
                        class="form-control" accept="image/*">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="colors[${typeIndex}][${colorIndex}][price]"
                        class="form-control" placeholder="Extra Price" value="0.00">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-remove remove-color">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

  colorsContainer.insertAdjacentHTML("beforeend", colorHTML);
}

// Event listeners
document.addEventListener("DOMContentLoaded", function () {
  // Add color button event
  document.addEventListener("click", function (e) {
    if (
      e.target.classList.contains("add-color") ||
      e.target.closest(".add-color")
    ) {
      const button = e.target.classList.contains("add-color")
        ? e.target
        : e.target.closest(".add-color");
      const typeIndex = button.getAttribute("data-type-index");
      addColorToType(typeIndex);
    }

    // Remove color button event
    if (
      e.target.classList.contains("remove-color") ||
      e.target.closest(".remove-color")
    ) {
      const colorItem = e.target.closest(".color-item");
      if (colorItem) {
        colorItem.remove();
      }
    }
  });
});
