<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIY Cabinet Color Builder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen p-4">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">DIY Cabinet Color Builder</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Color/Texture Palette -->
            <div class="lg:col-span-1">
                <h2 class="text-2xl font-semibold mb-4 text-gray-700">Colors & Textures</h2>
                <div class="bg-white rounded-lg shadow-lg p-4 space-y-4">
                    
                    <!-- Wood Textures -->
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Wood Finishes</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="color-item w-full h-12 bg-gradient-to-r from-amber-600 to-amber-800 rounded cursor-pointer border-2 border-transparent hover:border-gray-400 transition-all" 
                                 data-color="wood-dark" title="Dark Wood">
                                <div class="w-full h-full rounded bg-gradient-to-br from-amber-700 via-amber-800 to-amber-900 opacity-80"></div>
                            </div>
                            <div class="color-item w-full h-12 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded cursor-pointer border-2 border-transparent hover:border-gray-400 transition-all" 
                                 data-color="wood-light" title="Light Wood">
                                <div class="w-full h-full rounded bg-gradient-to-br from-yellow-300 via-yellow-500 to-yellow-700 opacity-80"></div>
                            </div>
                            <div class="color-item w-full h-12 bg-gradient-to-r from-red-800 to-red-900 rounded cursor-pointer border-2 border-transparent hover:border-gray-400 transition-all" 
                                 data-color="wood-mahogany" title="Mahogany">
                                <div class="w-full h-full rounded bg-gradient-to-br from-red-700 via-red-800 to-red-900 opacity-90"></div>
                            </div>
                            <div class="color-item w-full h-12 bg-gradient-to-r from-orange-700 to-orange-900 rounded cursor-pointer border-2 border-transparent hover:border-gray-400 transition-all" 
                                 data-color="wood-cherry" title="Cherry Wood">
                                <div class="w-full h-full rounded bg-gradient-to-br from-orange-600 via-red-700 to-red-800 opacity-90"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Solid Colors -->
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Solid Colors</h3>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="color-item w-full h-10 bg-white rounded cursor-pointer border-2 border-gray-300 hover:border-gray-400 transition-all" 
                                 data-color="white" title="White"></div>
                            <div class="color-item w-full h-10 bg-gray-800 rounded cursor-pointer border-2 border-transparent hover:border-gray-400 transition-all" 
                                 data-color="black" title="Black"></div>
                            <div class="color-item w-full h-10 bg-blue-600 rounded cursor-pointer border-2 border-transparent hover:border-gray-400 transition-all" 
                                 data-color="blue" title="Blue"></div>
                            <div class="color-item w-full h-10 bg-green-600 rounded cursor-pointer border-2 border-transparent hover:border-gray-400 transition-all" 
                                 data-color="green" title="Green"></div>
                            <div class="color-item w-full h-10 bg-red-600 rounded cursor-pointer border-2 border-transparent hover:border-gray-400 transition-all" 
                                 data-color="red" title="Red"></div>
                            <div class="color-item w-full h-10 bg-purple-600 rounded cursor-pointer border-2 border-transparent hover:border-gray-400 transition-all" 
                                 data-color="purple" title="Purple"></div>
                        </div>
                    </div>

                    <!-- Metallic Finishes -->
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Metallic Finishes</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="color-item w-full h-10 bg-gradient-to-r from-gray-400 to-gray-600 rounded cursor-pointer border-2 border-transparent hover:border-gray-400 transition-all" 
                                 data-color="silver" title="Silver">
                                <div class="w-full h-full rounded bg-gradient-to-br from-gray-300 via-gray-500 to-gray-600 opacity-90"></div>
                            </div>
                            <div class="color-item w-full h-10 bg-gradient-to-r from-yellow-500 to-yellow-700 rounded cursor-pointer border-2 border-transparent hover:border-gray-400 transition-all" 
                                 data-color="gold" title="Gold">
                                <div class="w-full h-full rounded bg-gradient-to-br from-yellow-400 via-yellow-600 to-yellow-800 opacity-90"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800"><strong>How to use:</strong> Click on a color/texture above, then click on any part of the cabinet to apply it!</p>
                    </div>
                </div>
            </div>

            <!-- Cabinet Editor -->
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-semibold mb-4 text-gray-700">Customize Your Cabinet</h2>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-center">
                        <svg width="400" height="500" viewBox="0 0 400 500" class="border-2 border-gray-200 rounded-lg">
                            <!-- Cabinet Frame Background -->
                            <rect x="20" y="20" width="360" height="460" fill="#f3f4f6" stroke="#d1d5db" stroke-width="2" rx="8"/>
                            
                            <!-- Back Panel -->
                            <rect id="back-panel" x="30" y="30" width="340" height="440" fill="#e5e7eb" stroke="#9ca3af" stroke-width="1" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="back" rx="4"/>
                            
                            <!-- Top Panel -->
                            <rect id="top-panel" x="25" y="20" width="350" height="25" fill="#d97706" stroke="#92400e" stroke-width="2" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="top" rx="4"/>
                            
                            <!-- Bottom Panel -->
                            <rect id="bottom-panel" x="25" y="455" width="350" height="25" fill="#d97706" stroke="#92400e" stroke-width="2" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="bottom" rx="4"/>
                            
                            <!-- Left Side Panel -->
                            <rect id="left-side" x="20" y="45" width="25" height="410" fill="#ea580c" stroke="#9a3412" stroke-width="2" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="left-side" rx="4"/>
                            
                            <!-- Right Side Panel -->
                            <rect id="right-side" x="355" y="45" width="25" height="410" fill="#ea580c" stroke="#9a3412" stroke-width="2" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="right-side" rx="4"/>
                            
                            <!-- Shelves -->
                            <rect id="shelf-1" x="50" y="150" width="300" height="8" fill="#16a34a" stroke="#166534" stroke-width="1" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="shelf-1" rx="2"/>
                            <rect id="shelf-2" x="50" y="250" width="300" height="8" fill="#16a34a" stroke="#166534" stroke-width="1" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="shelf-2" rx="2"/>
                            <rect id="shelf-3" x="50" y="350" width="300" height="8" fill="#16a34a" stroke="#166534" stroke-width="1" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="shelf-3" rx="2"/>
                            
                            <!-- Left Door -->
                            <rect id="left-door" x="55" y="55" width="140" height="180" fill="#2563eb" stroke="#1d4ed8" stroke-width="2" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="left-door" rx="4"/>
                            <circle cx="180" cy="145" r="3" fill="#374151"/>
                            
                            <!-- Right Door -->
                            <rect id="right-door" x="205" y="55" width="140" height="180" fill="#2563eb" stroke="#1d4ed8" stroke-width="2" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="right-door" rx="4"/>
                            <circle cx="220" cy="145" r="3" fill="#374151"/>
                            
                            <!-- Bottom Doors -->
                            <rect id="bottom-left-door" x="55" y="265" width="140" height="180" fill="#2563eb" stroke="#1d4ed8" stroke-width="2" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="bottom-left-door" rx="4"/>
                            <circle cx="180" cy="355" r="3" fill="#374151"/>
                            
                            <rect id="bottom-right-door" x="205" y="265" width="140" height="180" fill="#2563eb" stroke="#1d4ed8" stroke-width="2" 
                                  class="cabinet-part cursor-pointer hover:opacity-80 transition-opacity" data-part="bottom-right-door" rx="4"/>
                            <circle cx="220" cy="355" r="3" fill="#374151"/>
                            
                            <!-- Cabinet Labels -->
                            <text x="200" y="15" text-anchor="middle" class="text-xs fill-gray-600 font-medium">Kitchen Cabinet</text>
                        </svg>
                    </div>
                    
                    <!-- Selected Color Display -->
                    <div class="mt-6 flex items-center justify-center space-x-4">
                        <span class="text-sm font-medium text-gray-600">Selected Color:</span>
                        <div id="selected-color" class="w-8 h-8 bg-gray-300 border-2 border-gray-400 rounded"></div>
                        <span id="selected-color-name" class="text-sm text-gray-600">None selected</span>
                    </div>
                </div>

                <!-- Reset Button -->
                <div class="mt-4 flex justify-center">
                    <button id="reset-btn" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Reset Cabinet
                    </button>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold mb-3 text-gray-700">How to Use</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-600">
                <ul class="space-y-2">
                    <li class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span>Click on any color/texture from the left panel</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span>Then click on any part of the cabinet to apply</span>
                    </li>
                </ul>
                <ul class="space-y-2">
                    <li class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <span>Mix and match different colors for each part</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <span>Click "Reset Cabinet" to start over</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        let selectedColor = null;
        let selectedColorName = '';
        let selectedColorElement = null;
        
        // Color/texture definitions
        const colorMap = {
            'wood-dark': { fill: '#92400e', gradient: 'url(#wood-dark-gradient)' },
            'wood-light': { fill: '#fbbf24', gradient: 'url(#wood-light-gradient)' },
            'wood-mahogany': { fill: '#7f1d1d', gradient: 'url(#mahogany-gradient)' },
            'wood-cherry': { fill: '#c2410c', gradient: 'url(#cherry-gradient)' },
            'white': { fill: '#ffffff', stroke: '#d1d5db' },
            'black': { fill: '#1f2937', stroke: '#374151' },
            'blue': { fill: '#2563eb', stroke: '#1d4ed8' },
            'green': { fill: '#16a34a', stroke: '#166534' },
            'red': { fill: '#dc2626', stroke: '#b91c1c' },
            'purple': { fill: '#9333ea', stroke: '#7c3aed' },
            'silver': { fill: '#6b7280', gradient: 'url(#silver-gradient)' },
            'gold': { fill: '#d97706', gradient: 'url(#gold-gradient)' }
        };

        // Create gradients for textures
        function createGradients() {
            const svg = document.querySelector('svg');
            const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
            
            // Wood dark gradient
            const woodDark = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
            woodDark.setAttribute('id', 'wood-dark-gradient');
            woodDark.innerHTML = `
                <stop offset="0%" style="stop-color:#92400e;stop-opacity:1" />
                <stop offset="50%" style="stop-color:#78350f;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#451a03;stop-opacity:1" />
            `;
            
            // Wood light gradient
            const woodLight = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
            woodLight.setAttribute('id', 'wood-light-gradient');
            woodLight.innerHTML = `
                <stop offset="0%" style="stop-color:#fbbf24;stop-opacity:1" />
                <stop offset="50%" style="stop-color:#f59e0b;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#d97706;stop-opacity:1" />
            `;
            
            // Mahogany gradient
            const mahogany = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
            mahogany.setAttribute('id', 'mahogany-gradient');
            mahogany.innerHTML = `
                <stop offset="0%" style="stop-color:#7f1d1d;stop-opacity:1" />
                <stop offset="50%" style="stop-color:#991b1b;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#450a0a;stop-opacity:1" />
            `;
            
            // Cherry gradient
            const cherry = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
            cherry.setAttribute('id', 'cherry-gradient');
            cherry.innerHTML = `
                <stop offset="0%" style="stop-color:#c2410c;stop-opacity:1" />
                <stop offset="50%" style="stop-color:#ea580c;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#9a3412;stop-opacity:1" />
            `;
            
            // Silver gradient
            const silver = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
            silver.setAttribute('id', 'silver-gradient');
            silver.innerHTML = `
                <stop offset="0%" style="stop-color:#e5e7eb;stop-opacity:1" />
                <stop offset="50%" style="stop-color:#6b7280;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#374151;stop-opacity:1" />
            `;
            
            // Gold gradient
            const gold = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
            gold.setAttribute('id', 'gold-gradient');
            gold.innerHTML = `
                <stop offset="0%" style="stop-color:#fbbf24;stop-opacity:1" />
                <stop offset="50%" style="stop-color:#d97706;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#92400e;stop-opacity:1" />
            `;
            
            defs.appendChild(woodDark);
            defs.appendChild(woodLight);
            defs.appendChild(mahogany);
            defs.appendChild(cherry);
            defs.appendChild(silver);
            defs.appendChild(gold);
            
            svg.insertBefore(defs, svg.firstChild);
        }

        // Initialize gradients
        createGradients();
        
        // Add event listeners to color items
        document.querySelectorAll('.color-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remove previous selection
                if (selectedColorElement) {
                    selectedColorElement.classList.remove('ring-4', 'ring-blue-500');
                }
                
                // Set new selection
                selectedColor = this.getAttribute('data-color');
                selectedColorName = this.getAttribute('title');
                selectedColorElement = this;
                
                // Highlight selected color
                this.classList.add('ring-4', 'ring-blue-500');
                
                // Update selected color display
                updateSelectedColorDisplay();
            });
        });
        
        // Add event listeners to cabinet parts
        document.querySelectorAll('.cabinet-part').forEach(part => {
            part.addEventListener('click', function() {
                if (selectedColor) {
                    applyColorToPart(this, selectedColor);
                } else {
                    alert('Please select a color first!');
                }
            });
        });
        
        function applyColorToPart(element, colorKey) {
            const colorData = colorMap[colorKey];
            
            if (colorData.gradient) {
                element.setAttribute('fill', colorData.gradient);
            } else {
                element.setAttribute('fill', colorData.fill);
            }
            
            if (colorData.stroke) {
                element.setAttribute('stroke', colorData.stroke);
            }
        }
        
        function updateSelectedColorDisplay() {
            const selectedColorDiv = document.getElementById('selected-color');
            const selectedColorNameSpan = document.getElementById('selected-color-name');
            
            if (selectedColor) {
                const colorData = colorMap[selectedColor];
                if (selectedColor.includes('wood') || selectedColor.includes('silver') || selectedColor.includes('gold')) {
                    // For gradient colors, show a representative color
                    selectedColorDiv.className = selectedColorElement.className.replace('color-item', '') + ' w-8 h-8 border-2 border-gray-400 rounded';
                } else {
                    selectedColorDiv.style.backgroundColor = colorData.fill;
                    selectedColorDiv.className = 'w-8 h-8 border-2 border-gray-400 rounded';
                }
                selectedColorNameSpan.textContent = selectedColorName;
            }
        }
        
        // Reset button functionality
        document.getElementById('reset-btn').addEventListener('click', function() {
            // Reset all cabinet parts to original colors
            document.getElementById('back-panel').setAttribute('fill', '#e5e7eb');
            document.getElementById('top-panel').setAttribute('fill', '#d97706');
            document.getElementById('bottom-panel').setAttribute('fill', '#d97706');
            document.getElementById('left-side').setAttribute('fill', '#ea580c');
            document.getElementById('right-side').setAttribute('fill', '#ea580c');
            document.getElementById('shelf-1').setAttribute('fill', '#16a34a');
            document.getElementById('shelf-2').setAttribute('fill', '#16a34a');
            document.getElementById('shelf-3').setAttribute('fill', '#16a34a');
            document.getElementById('left-door').setAttribute('fill', '#2563eb');
            document.getElementById('right-door').setAttribute('fill', '#2563eb');
            document.getElementById('bottom-left-door').setAttribute('fill', '#2563eb');
            document.getElementById('bottom-right-door').setAttribute('fill', '#2563eb');
            
            // Reset selection
            if (selectedColorElement) {
                selectedColorElement.classList.remove('ring-4', 'ring-blue-500');
            }
            selectedColor = null;
            selectedColorName = '';
            selectedColorElement = null;
            
            // Reset display
            document.getElementById('selected-color').className = 'w-8 h-8 bg-gray-300 border-2 border-gray-400 rounded';
            document.getElementById('selected-color-name').textContent = 'None selected';
        });
    </script>
</body>
</html>