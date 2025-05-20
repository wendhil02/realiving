<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabinet Square Meter Calculator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Cabinet Square Meter Calculator</h1>
            
            <!-- Calculator Form -->
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Input Section -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Cabinet Dimensions</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Width (meters)</label>
                            <input type="number" id="width" step="0.01" min="0" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., 2.5">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Height (meters)</label>
                            <input type="number" id="height" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., 3.0">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Depth (meters) - Optional</label>
                            <input type="number" id="depth" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., 0.6">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Number of Panels</label>
                            <input type="number" id="panels" min="1" value="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="1">
                        </div>
                    </div>
                    
                    <!-- Pricing Section -->
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Pricing (Optional)</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Price per Square Meter (₱)</label>
                            <input type="number" id="pricePerSqm" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., 5000">
                        </div>
                    </div>
                    
                    <button onclick="calculate()" 
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition duration-200 font-semibold">
                        Calculate
                    </button>
                </div>
                
                <!-- Results Section -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Results</h2>
                    
                    <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Area per Panel:</span>
                            <span id="areaPerPanel" class="font-semibold text-lg">0.00 m²</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Area:</span>
                            <span id="totalArea" class="font-semibold text-lg text-blue-600">0.00 m²</span>
                        </div>
                        
                        <div class="flex justify-between items-center border-t pt-4">
                            <span class="text-gray-600">Volume (if depth provided):</span>
                            <span id="volume" class="font-semibold text-lg">0.00 m³</span>
                        </div>
                        
                        <div class="flex justify-between items-center border-t pt-4">
                            <span class="text-gray-600">Total Cost:</span>
                            <span id="totalCost" class="font-bold text-xl text-green-600">₱ 0.00</span>
                        </div>
                    </div>
                    
                    <!-- Material Breakdown -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Material Breakdown</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Standard Plywood (4x8 ft):</span>
                                <span id="plywoodSheets" class="font-semibold">0 sheets</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Melamine Board (4x8 ft):</span>
                                <span id="melamineSheets" class="font-semibold">0 sheets</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Saved Calculations -->
            <div class="mt-8 border-t pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Saved Calculations</h3>
                    <button onclick="clearHistory()" 
                            class="text-red-600 hover:text-red-800 text-sm underline">
                        Clear All
                    </button>
                </div>
                <div id="savedCalculations" class="space-y-2">
                    <!-- Saved calculations will appear here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        let savedCalculations = JSON.parse(localStorage.getItem('cabinetCalculations')) || [];

        function calculate() {
            const width = parseFloat(document.getElementById('width').value) || 0;
            const height = parseFloat(document.getElementById('height').value) || 0;
            const depth = parseFloat(document.getElementById('depth').value) || 0;
            const panels = parseInt(document.getElementById('panels').value) || 1;
            const pricePerSqm = parseFloat(document.getElementById('pricePerSqm').value) || 0;

            if (width <= 0 || height <= 0) {
                alert('Please enter valid width and height values.');
                return;
            }

            // Calculate areas
            const areaPerPanel = width * height;
            const totalArea = areaPerPanel * panels;
            const volume = depth > 0 ? width * height * depth * panels : 0;

            // Calculate cost
            const totalCost = totalArea * pricePerSqm;

            // Calculate material requirements (standard 4x8 ft sheets = 2.97 m²)
            const sheetArea = 2.97; // 4ft x 8ft in square meters
            const plywoodSheets = Math.ceil(totalArea / sheetArea);
            const melamineSheets = plywoodSheets; // Assuming same amount needed

            // Update display
            document.getElementById('areaPerPanel').textContent = areaPerPanel.toFixed(2) + ' m²';
            document.getElementById('totalArea').textContent = totalArea.toFixed(2) + ' m²';
            document.getElementById('volume').textContent = volume.toFixed(2) + ' m³';
            document.getElementById('totalCost').textContent = '₱ ' + totalCost.toLocaleString('en-PH', {minimumFractionDigits: 2});
            document.getElementById('plywoodSheets').textContent = plywoodSheets + ' sheets';
            document.getElementById('melamineSheets').textContent = melamineSheets + ' sheets';

            // Save calculation
            const calculation = {
                date: new Date().toLocaleDateString(),
                width: width,
                height: height,
                depth: depth,
                panels: panels,
                totalArea: totalArea,
                totalCost: totalCost,
                pricePerSqm: pricePerSqm
            };

            savedCalculations.unshift(calculation);
            if (savedCalculations.length > 10) {
                savedCalculations = savedCalculations.slice(0, 10);
            }
            
            localStorage.setItem('cabinetCalculations', JSON.stringify(savedCalculations));
            displaySavedCalculations();
        }

        function displaySavedCalculations() {
            const container = document.getElementById('savedCalculations');
            container.innerHTML = '';

            if (savedCalculations.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm">No saved calculations yet.</p>';
                return;
            }

            savedCalculations.forEach((calc, index) => {
                const div = document.createElement('div');
                div.className = 'bg-gray-50 p-3 rounded border text-sm';
                div.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div>
                            <strong>${calc.date}</strong> - ${calc.width}m × ${calc.height}m ${calc.depth > 0 ? '× ' + calc.depth + 'm' : ''} 
                            ${calc.panels > 1 ? '(' + calc.panels + ' panels)' : ''}
                        </div>
                        <div class="text-right">
                            <div class="font-semibold">${calc.totalArea.toFixed(2)} m²</div>
                            ${calc.pricePerSqm > 0 ? '<div class="text-green-600">₱ ' + calc.totalCost.toLocaleString('en-PH', {minimumFractionDigits: 2}) + '</div>' : ''}
                        </div>
                    </div>
                `;
                container.appendChild(div);
            });
        }

        function clearHistory() {
            if (confirm('Are you sure you want to clear all saved calculations?')) {
                savedCalculations = [];
                localStorage.removeItem('cabinetCalculations');
                displaySavedCalculations();
            }
        }

        // Load saved calculations on page load
        displaySavedCalculations();

        // Auto-calculate on input change
        ['width', 'height', 'depth', 'panels', 'pricePerSqm'].forEach(id => {
            document.getElementById(id).addEventListener('input', function() {
                if (document.getElementById('width').value && document.getElementById('height').value) {
                    calculate();
                }
            });
        });
    </script>
</body>
</html>