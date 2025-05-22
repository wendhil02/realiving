<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Kitchen Cabinet - Shopee Style</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .color-option {
            transition: all 0.2s ease;
        }
        .color-option:hover {
            transform: scale(1.1);
        }
        .color-option.selected {
            border: 2px solid #f59e0b;
            transform: scale(1.1);
        }
        .type-option {
            transition: all 0.2s ease;
        }
        .type-option.selected {
            background: #f59e0b;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-sm">
        <!-- Cabinet Product Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="relative">
                <img src="/api/placeholder/300/300" alt="Modern Kitchen Cabinet" class="w-full h-64 object-cover">
                <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                    BESTSELLER
                </div>
            </div>
            
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-2 text-gray-800">Modern Kitchen Cabinet</h3>
                <div class="flex items-center mb-3">
                    <span class="text-orange-500 text-lg font-bold">₱15,999</span>
                    <span class="text-gray-400 line-through ml-2 text-sm">₱18,999</span>
                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs ml-2">-16%</span>
                </div>
                
                <!-- Type Selection -->
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-2">Type:</p>
                    <div class="flex flex-wrap gap-2">
                        <button class="type-option px-3 py-1 border border-gray-300 rounded text-xs hover:bg-gray-100 selected" onclick="selectType(this)">
                            Wall Mount
                        </button>
                        <button class="type-option px-3 py-1 border border-gray-300 rounded text-xs hover:bg-gray-100" onclick="selectType(this)">
                            Floor Standing
                        </button>
                    </div>
                </div>
                
                <!-- Color Selection -->
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Color:</p>
                    <div class="flex gap-2">
                        <div class="color-option w-6 h-6 bg-white border-2 border-gray-300 rounded-full cursor-pointer selected" onclick="selectColor(this)" title="White"></div>
                        <div class="color-option w-6 h-6 bg-gray-800 border-2 border-gray-300 rounded-full cursor-pointer" onclick="selectColor(this)" title="Black"></div>
                        <div class="color-option w-6 h-6 bg-yellow-700 border-2 border-gray-300 rounded-full cursor-pointer" onclick="selectColor(this)" title="Wood"></div>
                    </div>
                </div>
                
                <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded font-medium transition-colors">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>

    <script>
        function selectType(button) {
            // Remove selected class from all type buttons
            const typeButtons = document.querySelectorAll('.type-option');
            typeButtons.forEach(btn => btn.classList.remove('selected'));
            
            // Add selected class to clicked button
            button.classList.add('selected');
        }

        function selectColor(colorDiv) {
            // Remove selected class from all color options
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(option => option.classList.remove('selected'));
            
            // Add selected class to clicked color
            colorDiv.classList.add('selected');
        }
    </script>
</body>
</html>