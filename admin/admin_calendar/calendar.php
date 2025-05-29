<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Calendar</title>
     <link rel="icon" type="image/png" sizes="32x32" href="../../logo/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
  
</head>

<body class="min-h-screen bg-gray-50">
    <div class="container mx-auto max-w-7xl">
        <?php
        session_start();
        include '../../connection/connection.php'; // database connection
        include '../checkrole.php';
        $appointments = [];

        if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
            // Redirect to login page if not logged in
            header("Location: ../../loginpage/index.php");
            exit();
        }

        // Allow only admin5
        require_role(['admin1', 'superadmin']);

        // Fetch appointments that are not marked as "done"
        $sql = "SELECT * FROM appointments WHERE status != 'done'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appointments[$row['appointment_date']][] = $row;
            }
        }

        include '../design/mainbody.php';

        // Check if there are appointments for today and display the notice
        $today = date("Y-m-d");

        // === Calendar Function ===
        function renderCalendar($appointments, $month, $year)
        {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $firstDayOfWeek = date('w', strtotime("$year-$month-01"));
            $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            // Get today's date for comparison
            $today = date("Y-m-d");

            // Create the calendar header with the days of the week
            echo '<div class="grid grid-cols-7 gap-2 mb-4 text-center font-semibold calendar-header p-2">';
            foreach ($daysOfWeek as $day) {
                echo "<div class='py-2'>" . substr($day, 0, 3) . "</div>";
            }
            echo '</div>';

            // Start the grid for the days of the month
            echo '<div class="grid grid-cols-7 gap-3">';

            // Empty space before the first day of the month
            for ($i = 0; $i < $firstDayOfWeek; $i++) {
                echo '<div class="p-4"></div>';
            }

            // Loop through each day of the month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf("%04d-%02d-%02d", $year, $month, $day);
                $hasAppointments = isset($appointments[$date]);
                $highlightClass = $hasAppointments ? 'has-appointment' : '';

                // Check if the date is today's date
                $isToday = ($date === $today) ? 'today' : '';

                // Day cell layout with conditional classes
                echo '<div id="day-' . $date . '" class="calendar-day p-3 border rounded-lg flex flex-col ' . $highlightClass . ' ' . $isToday . '">';
                echo '<div class="font-bold text-lg mb-1 flex justify-between items-center">';
                echo '<span>' . $day . '</span>';
                if ($isToday) {
                    echo '<span class="text-xs bg-green-500 text-white rounded-full px-2 py-0.5">Today</span>';
                }
                echo '</div>';

                if ($hasAppointments) {
                    foreach ($appointments[$date] as $appointment) {
                        $titleText = $appointment['title'];
                        $timeText = $appointment['time'];
                        $formattedTime = date("g:i A", strtotime($timeText));
                        $appointmentTime = strtotime($timeText);
                        $currentTime = time();

                        // Display appointment info only if the appointment hasn't passed and is not marked as "done"
                        if ($appointmentTime > $currentTime) {
                            echo '<div class="text-xs bg-blue-100 text-blue-800 rounded mt-1 p-1.5 appointment-item flex items-center">';
                            echo '<i class="fas fa-calendar-check mr-1 text-blue-600"></i> ';
                            echo '<span class="truncate">' . htmlspecialchars($titleText) . ' - ' . $formattedTime . '</span>';
                            echo '</div>';
                        }
                    }
                }

                echo '</div>';
            }

            echo '</div>'; // End the day grid
        }

        // Month and year
        $month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

        // Navigation for previous and next month
        $prevMonth = $month - 1;
        $nextMonth = $month + 1;
        $prevYear = $year;
        $nextYear = $year;

        if ($prevMonth == 0) {
            $prevMonth = 12;
            $prevYear--;
        }
        if ($nextMonth == 13) {
            $nextMonth = 1;
            $nextYear++;
        }

        // Check appointments for today
        $todayAppointments = isset($appointments[$today]) ? $appointments[$today] : [];

        // Filter appointments for today that haven't passed yet
        $futureAppointments = [];
        foreach ($todayAppointments as $appointment) {
            $appointmentTime = strtotime($appointment['time']);
            if ($appointmentTime > time()) {
                $futureAppointments[] = $appointment;
            }
        }

        // Display a notice if there are appointments for today (future appointments only)
        $noticeMessage = '';
        if (count($futureAppointments) > 0) {
            $noticeMessage = "You have " . count($futureAppointments) . " upcoming appointment(s) for today!";
        } else {
            $noticeMessage = "No upcoming appointments for today.";
        }
        ?>

        <main class="w-full mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold month-title"><?= date('F Y', mktime(0, 0, 0, $month, 1, $year)) ?></h1>
                <p class="text-gray-600 text-sm mt-1">Manage your appointments efficiently</p>
            </div>

            <!-- Notice Section -->
            <div class="flex justify-center items-center mb-8">
                <?php if (count($futureAppointments) > 0): ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm w-full max-w-2xl flex items-center">
                    <div class="text-yellow-500 mr-3">
                        <i class="fas fa-bell text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-yellow-800">Today's Schedule</h3>
                        <p class="text-yellow-700"><?= htmlspecialchars($noticeMessage) ?></p>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md shadow-sm w-full max-w-2xl flex items-center">
                    <div class="text-blue-500 mr-3">
                        <i class="fas fa-info-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-800">Today's Schedule</h3>
                        <p class="text-blue-700"><?= htmlspecialchars($noticeMessage) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Top Control Bar -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8 bg-white p-5 rounded-xl shadow-sm">
                <div class="flex flex-wrap items-center gap-3">
                    <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="btn-action bg-white border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-chevron-left mr-2"></i> Prev
                    </a>
                    
                    <form method="GET" class="flex flex-wrap items-center gap-2">
                        <select name="month" class="border border-gray-300 rounded-lg p-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>

                        <select name="year" class="border border-gray-300 rounded-lg p-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php
                            $currentYear = date('Y');
                            for ($y = $currentYear - 5; $y <= $currentYear + 5; $y++): ?>
                                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endfor; ?>
                        </select>

                        <button type="submit" class="btn-gradient text-white font-medium py-2 px-4 rounded-lg">
                            Apply
                        </button>
                    </form>
                    
                    <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="btn-action bg-white border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center">
                        Next <i class="fas fa-chevron-right ml-2"></i>
                    </a>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <button onclick="openModal()" class="btn-gradient text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add Appointment
                    </button>
                    <button onclick="viewAppointments()" class="btn-action bg-white border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-list-ul mr-2"></i> View All
                    </button>
                </div>
            </div>

            <!-- Calendar Section with Box Shadow -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <?php renderCalendar($appointments, $month, $year); ?>
            </div>

        </main>

        <!-- Add Appointment Modal -->
        <div id="appointmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 modal-container">
            <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md relative">
                <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white py-4 px-6 rounded-t-xl">
                    <h2 class="text-xl font-bold">Add New Appointment</h2>
                </div>
                <div class="mt-14">
                    <form action="add_appointment.php" method="POST" class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-heading text-gray-400"></i>
                                </div>
                                <input type="text" name="title" class="pl-10 w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter appointment title" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input type="date" name="appointment_date" class="pl-10 w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                                <input type="time" name="time" class="pl-10 w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                        </div>
                        <div class="pt-3 flex justify-between">
                            <button type="button" onclick="closeModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-6 rounded-lg transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="btn-gradient text-white font-medium py-2 px-6 rounded-lg">
                                Save Appointment
                            </button>
                        </div>
                    </form>
                </div>
                <button onclick="closeModal()" class="absolute top-3 right-3 text-white hover:text-gray-200 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- View Appointments Modal -->
        <div id="appointmentsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 modal-container">
            <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-hidden relative">
                <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white py-4 px-6 rounded-t-xl">
                    <h2 class="text-xl font-bold">All Appointments</h2>
                </div>
                <div class="mt-14 overflow-y-auto max-h-[70vh] pr-2">
                    <?php if (count($appointments) > 0): ?>
                        <div class="divide-y divide-gray-200">
                            <?php foreach ($appointments as $date => $appointmentList): ?>
                                <div class="py-4">
                                    <div class="font-semibold text-lg text-gray-800 mb-2 flex items-center">
                                        <i class="fas fa-calendar-day mr-2 text-blue-600"></i>
                                        <?= date('l, F j, Y', strtotime($date)) ?>
                                    </div>
                                    <ul class="ml-6 space-y-2">
                                        <?php foreach ($appointmentList as $index => $appointment): ?>
                                            <?php
                                            $titleText = $appointment['title'];
                                            $timeText = $appointment['time'];
                                            $formattedTime = date("g:i A", strtotime($timeText));
                                            $checkboxId = "checkbox_" . md5($date . $index);
                                            $isPast = strtotime($date . ' ' . $timeText) < time();
                                            $statusClass = $appointment['status'] == 'done' ? 'line-through text-green-600' : ($isPast ? 'text-red-500' : 'text-gray-700');
                                            ?>
                                            <li class="text-sm flex items-center gap-3 bg-gray-50 p-3 rounded-lg hover:bg-gray-100 transition-all">
                                                <input type="checkbox" id="<?= $checkboxId ?>" class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" <?= $appointment['status'] == 'done' ? 'checked' : '' ?> onchange="toggleDone(this, '<?= $date ?>', '<?= addslashes($titleText) ?>')">
                                                <label for="<?= $checkboxId ?>" class="flex-1 flex items-center <?= $statusClass ?>">
                                                    <span class="mr-2"><?= htmlspecialchars($titleText) ?></span>
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                        <i class="fas fa-clock mr-1"></i><?= $formattedTime ?>
                                                    </span>
                                                </label>
                                                <?php if ($isPast && $appointment['status'] != 'done'): ?>
                                                    <span class="text-xs text-red-500">Overdue</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-10">
                            <div class="text-gray-400 text-5xl mb-3"><i class="far fa-calendar-times"></i></div>
                            <p class="text-gray-500 text-lg">No appointments scheduled</p>
                            <p class="text-gray-400 text-sm mt-2">Your schedule is clear. Add new appointments to get started.</p>
                            <button onclick="closeModalAppointments(); openModal();" class="btn-gradient text-white font-medium py-2 px-6 rounded-lg mt-4">
                                Add New Appointment
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
                <button onclick="closeModalAppointments()" class="absolute top-3 right-3 text-white hover:text-gray-200 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

  <script src="js/calendar.js"></script>
</body>

</html>