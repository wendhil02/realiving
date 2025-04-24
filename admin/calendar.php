<?php
session_start();
include '../connection/connection.php'; // database connection

$appointments = [];

// Fetch appointments that are not marked as "done"
$sql = "SELECT * FROM appointments WHERE status != 'done'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[$row['appointment_date']][] = $row;
    }
}

include 'design/siddebarmain.php';

// Check if there are appointments for today and display the notice
$today = date("Y-m-d");

// === Calendar Function ===
function renderCalendar($appointments, $month, $year)
{
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $firstDayOfWeek = date('w', strtotime("$year-$month-01"));
    $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    // Get today's date for comparison
    $today = date("Y-m-d");

    // Create the calendar header with the days of the week
    echo '<div class="grid grid-cols-7 gap-2 mb-4 text-center font-semibold">';
    foreach ($daysOfWeek as $day) {
        echo "<div>$day</div>";
    }
    echo '</div>';

    // Start the grid for the days of the month
    echo '<div class="grid grid-cols-7 gap-2">';

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
        echo '<div id="day-' . $date . '" class="calendar-day p-4 border rounded-lg min-h-[100px] flex flex-col ' . $highlightClass . ' ' . $isToday . '">';
        echo '<div class="font-bold text-xl mb-2">' . $day . '</div>';

        if ($hasAppointments) {
            foreach ($appointments[$date] as $appointment) {
                $titleText = $appointment['title'];
                $timeText = $appointment['time'];
                $formattedTime = date("g:i A", strtotime($timeText));
                $appointmentTime = strtotime($timeText);
                $currentTime = time();

                // Display appointment info only if the appointment hasn't passed and is not marked as "done"
                if ($appointmentTime > $currentTime) {
                    echo '<div class="text-xs bg-blue-100 text-blue-800 rounded mt-1 p-1">';
                    echo htmlspecialchars($titleText) . ' - ' . $formattedTime;
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

<!-- Add the notice here, above the calendar -->
<div class="flex justify-center items-center h-full">
    <div class="bg-green-100 text-yellow-800 rounded p-4 mb-6 text-center mx-auto max-w-[600px]">
        <strong>Notice:</strong> <?= htmlspecialchars($noticeMessage) ?>
    </div>
</div>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Calendar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .done-date {
            background-color: #bbf7d0 !important; /* Tailwind's green-200 */
        }

        .today {
    background-color: #d1fae5; /* Light green for today */
}

.mt-4 {
    margin-top: 1rem;
}

.bg-yellow-100 {
    background-color: #fefcbf; /* Yellow background for notice */
}

.text-yellow-800 {
    color: #b45309; /* Darker yellow text */
}

    </style>
</head>

<body class="flex min-h-screen">
<main class="flex w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full">
        <!-- Top Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            
            <form method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                <select name="month" class="border border-gray-300 rounded-md p-2 w-full sm:w-auto">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <select name="year" class="border border-gray-300 rounded-md p-2 w-full sm:w-auto">
                    <?php
                    $currentYear = date('Y');
                    for ($y = $currentYear - 5; $y <= $currentYear + 5; $y++): ?>
                        <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md w-full sm:w-auto">
                    Go
                </button>
            </form>

            <div class="flex flex-wrap gap-2 justify-center sm:justify-end w-full sm:w-auto">
                <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                    Prev
                </a>
                <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                    Next
                </a>
                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                    Add Appointment
                </button>
                <button onclick="viewAppointments()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                    View Appointments
                </button>
            </div>
        </div>

        <!-- Appointment Modal -->
        <div id="appointmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
                <h2 class="text-xl font-bold mb-6 text-center">Add Appointment</h2>
                <form action="add_appointment.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Title</label>
                        <input type="text" name="title" class="w-full border p-2 rounded-md" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Date</label>
                        <input type="date" name="appointment_date" class="w-full border p-2 rounded-md" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Time</label>
                        <input type="time" name="time" class="w-full border p-2 rounded-md" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg">Save</button>
                    </div>
                </form>
                <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">
                    ✖
                </button>
            </div>
        </div>

        <!-- Appointments Modal -->
        <div id="appointmentsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md max-h-[90vh] overflow-hidden relative">
                <h2 class="text-xl font-bold mb-6 text-center">Appointments</h2>
                <div class="overflow-y-auto max-h-[70vh] pr-2">
                    <ul class="space-y-4">
                        <?php if (count($appointments) > 0): ?>
                            <?php foreach ($appointments as $date => $appointmentList): ?>
                                <li class="border-b pb-2">
                                    <div class="font-semibold"><?= date('F j, Y', strtotime($date)) ?></div>
                                    <ul class="ml-4 mt-2">
                                        <?php foreach ($appointmentList as $index => $appointment): ?>
                                            <?php
                                            $titleText = $appointment['title'];
                                            $timeText = $appointment['time'];
                                            $formattedTime = date("g:i A", strtotime($timeText));
                                            $checkboxId = "checkbox_" . md5($date . $index);
                                            ?>
                                            <li class="text-sm text-gray-700 flex items-center gap-2">
                                                <input type="checkbox" id="<?= $checkboxId ?>" <?= $appointment['status'] == 'done' ? 'checked' : '' ?> onchange="toggleDone(this, '<?= $date ?>', '<?= addslashes($titleText) ?>')">
                                                <label for="<?= $checkboxId ?>" class="flex-1">
                                                    <?= htmlspecialchars($titleText) ?> - <?= $formattedTime ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="text-center text-gray-500">No appointments scheduled.</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <button onclick="closeModalAppointments()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">
                    ✖
                </button>
            </div>
        </div>
        <!-- Render Calendar -->
        <?php renderCalendar($appointments, $month, $year); ?>
    </div>
</main>

<script>

 
function openModal() {
    document.getElementById('appointmentModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('appointmentModal').classList.add('hidden');
}
function viewAppointments() {
    document.getElementById('appointmentsModal').classList.remove('hidden');
}
function closeModalAppointments() {
    document.getElementById('appointmentsModal').classList.add('hidden');
}

function toggleDone(checkbox, date) {
    const label = checkbox.nextElementSibling;
    const dayDiv = document.getElementById('day-' + date);
    
    if (checkbox.checked) {
        label.classList.add('text-green-600', 'line-through');
        if (dayDiv) dayDiv.classList.add('done-date');
    } else {
        label.classList.remove('text-green-600', 'line-through');

        // Check if other checkboxes of same date are still checked
        const checkboxes = document.querySelectorAll('input[type="checkbox"][onchange*="' + date + '"]');
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
        if (!anyChecked && dayDiv) {
            dayDiv.classList.remove('done-date');
        }
    }
}


function toggleDone(checkbox, date, title) {
    const label = checkbox.nextElementSibling;
    const dayDiv = document.getElementById('day-' + date);
    
    const status = checkbox.checked ? 'done' : 'pending';

    // AJAX call para isave yung status
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_status.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('date=' + encodeURIComponent(date) + '&title=' + encodeURIComponent(title) + '&status=' + status);

    xhr.onload = function () {
        if (xhr.status === 200 && xhr.responseText === 'success') {
            if (status === 'done') {
                label.classList.add('text-green-600', 'line-through');
                if (dayDiv) dayDiv.classList.add('done-date');
            } else {
                label.classList.remove('text-green-600', 'line-through');

                const checkboxes = document.querySelectorAll('input[type="checkbox"][onchange*="' + date + '"]');
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                if (!anyChecked && dayDiv) {
                    dayDiv.classList.remove('done-date');
                }
            }
        } else {
            alert('Failed to update appointment status.');
            checkbox.checked = !checkbox.checked; // Revert if failed
        }
    };
}

</script>

</body>
</html>
