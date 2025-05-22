<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Walk-in Appointment Scheduler</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen font-sans">
    <div class="container mx-auto px-4 py-12">
        <header class="mb-10 text-center">
            <div class="flex justify-center mb-4">
                <div class="bg-blue-600 text-white rounded-full p-4 shadow-lg">
                    <i class="fas fa-calendar-check text-3xl"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Company Appointment Scheduler</h1>
            <p class="text-gray-600 max-w-md mx-auto">Schedule your walk-in appointment efficiently and get confirmation instantly</p>
        </header>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="md:flex">
                    <!-- Left Info Panel -->
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-800 text-white p-8 md:w-2/5 flex flex-col justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-6">Visit Information</h2>
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="bg-blue-500 bg-opacity-20 rounded-full p-2 mr-4">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">Office Hours</h3>
                                        <p class="text-blue-100">Monday - Friday: 9AM - 5PM</p>
                                        <p class="text-blue-100 font-bold">Closed on Sundays</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-blue-500 bg-opacity-20 rounded-full p-2 mr-4">
                                        <i class="fas fa-map-marker-alt text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">Location</h3>
                                        <p class="text-blue-100">123 Business Ave., Suite 200</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-blue-500 bg-opacity-20 rounded-full p-2 mr-4">
                                        <i class="fas fa-phone-alt text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">Contact</h3>
                                        <p class="text-blue-100">support@company.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8">
                            <p class="text-sm text-blue-100 italic">We look forward to welcoming you to our office!</p>
                        </div>
                    </div>

                    <!-- Right Form Panel -->
                    <div class="p-8 md:w-3/5">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Schedule Your Visit</h2>
                        <form id="appointmentForm" method="post" action="process_appointment.php" class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="name" class="block text-gray-700 font-medium mb-1 text-sm">Full Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" id="name" name="name" class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                </div>
                                <div>
                                    <label for="email" class="block text-gray-700 font-medium mb-1 text-sm">Email Address</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" id="email" name="email" class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="phone" class="block text-gray-700 font-medium mb-1 text-sm">Phone Number</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone text-gray-400"></i>
                                        </div>
                                        <input type="tel" id="phone" name="phone" class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                </div>
                                <div>
                                    <label for="purpose" class="block text-gray-700 font-medium mb-1 text-sm">Purpose of Visit</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-briefcase text-gray-400"></i>
                                        </div>
                                        <select id="purpose" name="purpose" class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none" required>
                                            <option value="" disabled selected>Select purpose</option>
                                            <option value="inquiry">General Inquiry</option>
                                            <option value="consultation">Business Consultation</option>
                                            <option value="meeting">Client Meeting</option>
                                            <option value="interview">Interview</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="date" class="block text-gray-700 font-medium mb-1 text-sm">Preferred Date</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar text-gray-400"></i>
                                        </div>
                                        <input type="date" id="date" name="date" class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                    <p id="dateWarning" class="text-red-500 text-xs mt-1 hidden">Sorry, we are closed on Sundays.</p>
                                </div>
                                <div>
                                    <label for="time" class="block text-gray-700 font-medium mb-1 text-sm">Preferred Time</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-clock text-gray-400"></i>
                                        </div>
                                        <select id="time" name="time" class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none" required>
                                            <option value="" disabled selected>Select time slot</option>
                                            <option value="9:00">9:00 AM</option>
                                            <option value="10:00">10:00 AM</option>
                                            <option value="11:00">11:00 AM</option>
                                            <option value="13:00">1:00 PM</option>
                                            <option value="14:00">2:00 PM</option>
                                            <option value="15:00">3:00 PM</option>
                                            <option value="16:00">4:00 PM</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="notes" class="block text-gray-700 font-medium mb-1 text-sm">Additional Notes</label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-comment-alt text-gray-400"></i>
                                    </div>
                                    <textarea id="notes" name="notes" rows="3" class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" id="submitBtn" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-medium py-3 px-4 rounded-lg hover:opacity-90 transition duration-300 shadow-md flex items-center justify-center">
                                    <i class="fas fa-calendar-check mr-2"></i>
                                    Schedule Appointment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Calendar Availability Preview -->
            <div class="mt-8 bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Availability This Week</h3>
                <div class="grid grid-cols-5 gap-2">
                    <div class="text-center">
                        <div class="font-medium text-gray-800">Mon</div>
                        <div class="mt-2 space-y-1">
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">9:00 AM</div>
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">11:00 AM</div>
                            <div class="bg-red-100 text-red-800 text-xs py-1 px-2 rounded-md">1:00 PM</div>
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">3:00 PM</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="font-medium text-gray-800">Tue</div>
                        <div class="mt-2 space-y-1">
                            <div class="bg-red-100 text-red-800 text-xs py-1 px-2 rounded-md">9:00 AM</div>
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">11:00 AM</div>
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">1:00 PM</div>
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">3:00 PM</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="font-medium text-gray-800">Wed</div>
                        <div class="mt-2 space-y-1">
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">9:00 AM</div>
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">11:00 AM</div>
                            <div class="bg-red-100 text-red-800 text-xs py-1 px-2 rounded-md">1:00 PM</div>
                            <div class="bg-red-100 text-red-800 text-xs py-1 px-2 rounded-md">3:00 PM</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="font-medium text-gray-800">Thu</div>
                        <div class="mt-2 space-y-1">
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">9:00 AM</div>
                            <div class="bg-red-100 text-red-800 text-xs py-1 px-2 rounded-md">11:00 AM</div>
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">1:00 PM</div>
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">3:00 PM</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="font-medium text-gray-800">Fri</div>
                        <div class="mt-2 space-y-1">
                            <div class="bg-red-100 text-red-800 text-xs py-1 px-2 rounded-md">9:00 AM</div>
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">11:00 AM</div>
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">1:00 PM</div>
                            <div class="bg-green-100 text-green-800 text-xs py-1 px-2 rounded-md">3:00 PM</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-center space-x-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-100 rounded-full mr-1"></div>
                        <span class="text-gray-600">Available</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-100 rounded-full mr-1"></div>
                        <span class="text-gray-600">Booked</span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <span class="text-red-500 font-medium">Note: We are closed on Sundays</span>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div id="confirmationModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 max-w-md shadow-2xl transform transition-all">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                        <i class="fas fa-check-circle text-3xl text-green-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Appointment Scheduled!</h2>
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <p class="text-gray-700 mb-2">Your walk-in appointment has been scheduled successfully.</p>
                        <p class="text-gray-700">We'll send a confirmation to your email shortly.</p>
                    </div>
                    <button id="closeModal" class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-medium py-2 px-6 rounded-lg hover:opacity-90 transition duration-300 shadow-md">
                        <i class="fas fa-check mr-2"></i>Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>© 2025 Company Name. All rights reserved.</p>
            <div class="mt-4 flex justify-center space-x-4">
                <a href="#" class="text-gray-300 hover:text-white">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="text-gray-300 hover:text-white">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="text-gray-300 hover:text-white">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="#" class="text-gray-300 hover:text-white">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </footer>

    <script>
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').setAttribute('min', today);
        
        // Function to check if a date is Sunday
        function isSunday(dateString) {
            const date = new Date(dateString);
            return date.getDay() === 0;  // 0 represents Sunday
        }
        
        // Disable Sundays in the date picker
        document.getElementById('date').addEventListener('change', function() {
            const selectedDate = this.value;
            const dateWarning = document.getElementById('dateWarning');
            const submitBtn = document.getElementById('submitBtn');
            
            if (isSunday(selectedDate)) {
                this.setCustomValidity('We are closed on Sundays. Please select another day.');
                dateWarning.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50');
            } else {
                this.setCustomValidity('');
                dateWarning.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50');
            }
        });
        
        // For cases where form is submitted via AJAX and not PHP form submission
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            const dateInput = document.getElementById('date');
            
            // Check if selected date is Sunday before submission
            if (isSunday(dateInput.value)) {
                e.preventDefault();
                dateInput.setCustomValidity('We are closed on Sundays. Please select another day.');
                document.getElementById('dateWarning').classList.remove('hidden');
                return;
            }
            
            // This will only run if the form is not properly submitted to process_appointment.php
            // Acts as a fallback
            if (!this.getAttribute('data-submitted')) {
                e.preventDefault();
                
                // Create FormData object to collect form data
                const formData = new FormData(this);
                
                // Send data via fetch API as a backup method
                fetch('process_appointment.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('confirmationModal').classList.remove('hidden');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('confirmationModal').classList.add('hidden');
            document.getElementById('appointmentForm').reset();
        });
    </script>
</body>
</html>