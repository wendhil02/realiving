<div id="productModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50">
    <div id="modalContent"
        class="bg-white rounded-xl p-8 max-w-screen-xl w-3/4 h-3/4 overflow-y-auto transform transition-all duration-300 scale-95 opacity-0 translate-y-4 shadow-lg">
        
        <div class="flex w-full h-full">
            <!-- Product Image -->
            <div class="w-1/2 pr-6 flex justify-center items-center">
                <img id="modalImage" class="w-full h-full object-contain rounded-lg shadow-md" src="" alt="Product Image">
            </div>

            <!-- Product Details -->
            <div class="w-1/2 flex flex-col justify-center">
                <h2 id="modalName" class="text-6xl font-bold mb-6 break-words">Product Name</h2>
                <p id="modalDescription" class="text-lg mb-6 break-words text-gray-700">Product Description</p>

                <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                    <div><span class="text-gray-500">Size:</span> <span id="modalSize" class="text-gray-800">-</span></div>
                    <div><span class="text-gray-500">Price:</span> <span id="modalPrice" class="text-green-600 font-bold">₱0.00</span></div>
                    <div><span class="text-gray-500">Supplier:</span> <span id="modalSupplier" class="text-gray-800">-</span></div>
                    <div><span class="text-gray-500">Contact:</span> <span id="modalContact" class="text-gray-800">-</span></div>
                    <div><span class="text-gray-500">Serial #:</span> <span id="modalSerial" class="text-gray-800">-</span></div>
                </div>

                <div class="text-center mt-auto">
                    <button onclick="closeModal()" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
