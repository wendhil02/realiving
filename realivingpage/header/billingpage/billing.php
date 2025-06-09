<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Lookup</title>
    <link rel="stylesheet" href="css/billing.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'pulse-soft': 'pulseSoft 2s infinite',
                        'modal-appear': 'modalAppear 0.3s ease-out',
                    }
                }
            }
        }
    </script>

</head>

<body class="min-h-screen bg-white">
    <!-- Background Pattern -->
     <?php include '../headernav.php'; ?>
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-2xl">
            <!-- Header Section -->
            <div class="text-center mb-8 animate-fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-4 border-2 border-blue-500">
                    <img src="../../img/logo.png" alt="Logo" class="w-10 h-10 object-contain">
                </div>

                <h1 class="text-4xl font-bold text-blue-800 mb-2">Billing Portal</h1>
                <p class="text-black text-lg">Submit your receipt and track project payments</p>
            </div>

            <!-- Main Form Card -->
            <div class="glass-effect rounded-2xl shadow-2xl p-8 card-hover animate-slide-up">
                <form id="billingForm" enctype="multipart/form-data" class="space-y-6">

                    <!-- Billing Code Section -->
                    <div class="space-y-2">
                        <label for="reference" class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                            Billing Code (Reference Number)
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="reference"
                                name="reference_number"
                                onblur="fetchDetails()"
                                required
                                class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-xl input-focus focus:outline-none focus:ring-0 focus:border-primary-500 bg-white/80"
                                placeholder="Enter your billing code">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Client Information Grid -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Client Name -->
                        <div class="space-y-2">
                            <label for="clientname" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Client Name
                            </label>
                            <input
                                type="text"
                                id="clientname"
                                name="clientname"
                                readonly
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50/80 text-gray-600 cursor-not-allowed">
                        </div>

                        <!-- Total Project Cost -->
                        <div class="space-y-2">
                            <label for="total_cost" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Total Project Cost
                            </label>
                            <input
                                type="text"
                                id="total_cost"
                                name="total_project_cost"
                                readonly
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50/80 text-gray-600 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Remaining Balance - Full Width -->
                    <div class="space-y-2">
                        <label for="remaining_balance" class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Remaining Balance
                        </label>
                        <input
                            type="text"
                            id="remaining_balance"
                            name="remaining_balance"
                            readonly
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50/80 text-gray-600 cursor-not-allowed">
                    </div>

                    <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-semibold text-yellow-800 mb-1">Reminder</h3>
                                <p class="text-sm text-yellow-700">
                                    Only <strong>receipt images</strong> are allowed to be uploaded here. All uploaded files will be
                                    <strong>reviewed by the admin</strong> before they are approved or received.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Upload Receipt Image
                        </label>

                        <div class="border-3 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-400 hover:bg-primary-50/30 transition-all duration-300 cursor-pointer group"
                            id="dropZone"
                            ondrop="dropHandler(event);"
                            ondragover="dragOverHandler(event);"
                            ondragenter="dragEnterHandler(event);"
                            ondragleave="dragLeaveHandler(event);">

                            <div id="uploadContent">
                                <div class="mx-auto h-16 w-16 text-gray-400 mb-4 group-hover:text-primary-500 transition-colors duration-300">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 48 48" class="w-full h-full">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2 group-hover:text-primary-700 transition-colors duration-300">Drop your receipt here</h3>
                                <p class="text-gray-500 mb-2">or click to browse files</p>
                                <p class="text-xs text-red-600 mb-4 font-medium">⚠️ Receipt images only</p>
                                <button type="button" onclick="document.getElementById('receipt').click()"
                                    class="inline-flex items-center px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Browse Files
                                </button>
                                <p class="text-xs text-gray-400 mt-3">Supports PNG, JPG, GIF up to 10MB</p>
                            </div>

                            <!-- Image Preview -->
                            <div id="imagePreview" class="hidden">
                                <div class="relative inline-block">
                                    <img id="previewImg" src="" alt="Receipt Preview" class="max-w-full max-h-64 mx-auto rounded-lg border-2 border-gray-200 shadow-lg">
                                    <button type="button" onclick="removeImage()"
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg transition-all duration-200 transform hover:scale-110">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-4">
                                    <p id="fileName" class="text-sm text-gray-600 font-medium"></p>
                                    <p class="text-xs text-gray-500 mt-1">Pending admin review</p>
                                </div>
                            </div>
                        </div>

                        <input type="file" id="receipt" name="receipt_image" accept="image/*" onchange="handleFileSelect(event)" class="hidden">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit" onclick="showPaymentProof(event)" id="submitBtn"
                            class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-green-500/50 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span id="submitText">Submit Receipt</span>
                            <div id="loadingSpinner" class="hidden ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </button>

                        <button type="button" onclick="resetForm()"
                            class="flex-1 sm:flex-none bg-gray-500 hover:bg-gray-600 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-gray-500/50 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset Form
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-white/70">
                <p class="text-sm">Secure • Fast • Reliable</p>
            </div>
        </div>
    </div>

    <!-- Payment Proof Modal -->
    <div id="paymentProofModal" class="fixed inset-0 z-50 hidden modal-overlay flex items-center justify-center p-4">
        <div class="receipt-modal rounded-2xl max-w-md w-full animate-modal-appear p-8">
            <!-- Receipt Header -->
            <div class="text-center border-b-2 border-dashed border-gray-300 pb-6 mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Payment Submitted</h2>
                <p class="text-gray-600">Receipt submitted for review</p>
                <div class="mt-4 text-xs text-gray-500">
                    <span id="proofTimestamp"></span>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="space-y-4 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Reference Number:</span>
                    <span class="font-semibold text-gray-800" id="proofReference">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Client Name:</span>
                    <span class="font-semibold text-gray-800" id="proofClientName">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Project Cost:</span>
                    <span class="font-semibold text-gray-800" id="proofTotalCost">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Remaining Balance:</span>
                    <span class="font-semibold text-gray-800" id="proofRemainingBalance">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Receipt File:</span>
                    <span class="font-semibold text-gray-800" id="proofFileName">-</span>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-yellow-800 font-medium text-sm">Pending admin review</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="takeScreenshot()" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Take Screenshot for Your Records
                </button>
                
                <div class="flex space-x-3">
                    <button onclick="proceedWithSubmission()" 
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200">
                        Continue & Submit
                    </button>
                    <button onclick="closeModal()" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200">
                        Cancel
                    </button>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="mt-6 text-xs text-gray-500 text-center border-t border-dashed border-gray-300 pt-4">
                <p>📱 <strong>Save this screen:</strong> Take a screenshot as proof of your payment submission</p>
                <p class="mt-1">You will receive an email confirmation once reviewed</p>
            </div>
        </div>
    </div>

    <script src="js/billing.js"></script>
   


</body>
</html>        