<?php
session_start();

include '../design/mainbody.php';
include '../checkrole.php';
include '../../connection/connection.php';

// Allow only admin5
require_role(['admin5','superadmin']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>

<body class="bg-gray-200">

<section class="py-5">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-wrap justify-between gap-4">

      <!-- Pie Chart: New vs Old Clients -->
      <div class="bg-white shadow rounded-lg p-4 flex flex-col items-center w-full sm:w-[30%] md:w-[28%] ml-4">
        <h2 class="text-xl font-semibold text-center text-gray-700 mb-4">Client Type</h2>
        <canvas id="combinedClientChart" width="150" height="150"></canvas>
      </div>

      <!-- Total Clients Counter -->
      <div class="bg-white shadow rounded-lg p-4 flex flex-col items-center justify-center w-full sm:w-[30%] md:w-[28%]">
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Total Clients</h2>
        <span id="totalClientsNumber" class="text-3xl font-bold text-gray-900">0</span>
      </div>

      <!-- Pie Chart: Completed vs Incomplete Clients -->
      <div class="bg-white shadow rounded-lg p-4 flex flex-col items-center w-full sm:w-[30%] md:w-[28%] mr-4">
        <h2 class="text-xl font-semibold text-center text-gray-700 mb-4">Status Overview</h2>
        <canvas id="statusClientChart" width="150" height="150"></canvas>
      </div>

    </div>
  </div>
</section>

    <div class="flex justify-center">
        <div class="mb-5 w-full max-w-md mt-2">
            <div class="flex items-center w-full bg-white rounded-lg shadow px-4 py-2">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search here..."
                    class="w-full bg-transparent focus:outline-none text-gray-700" />
                <button id="searchBtn" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Sort Dropdown -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 ml-5">
        <div class="flex items-center space-x-2">
            <label for="sortOrder" class="text-gray-700 font-medium">Sort by:</label>
            <select id="sortOrder" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm">
                <option value="asc">A to Z</option>
                <option value="desc">Z to A</option>
            </select>
        </div>
    </div>

    <!-- Client Records Table -->
    <div class="bg-white shadow-xl p-8 w-full overflow-x-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Client Records</h2>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md mt-6">
    <table class="min-w-full table-auto divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Reference No.</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Project Name</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Last Updated</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Action</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100" id="clientTableBody">
            <?php
            // Connect to your database (make sure $conn is already initialized)
            $result = $conn->query("
                SELECT u.*, 
                    (SELECT COUNT(*) FROM step_updates s WHERE s.client_id = u.id AND s.step = 10) AS step10_done 
                FROM user_info u 
                ORDER BY u.created_at DESC
            ");

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $clientLink = 'client_update.php?id=' . urlencode($row['id']);
            ?>
                    <tr onclick="window.location.href='<?php echo $clientLink; ?>'" class="client-row hover:bg-gray-100 cursor-pointer transition duration-200 group">

                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 group-hover:font-semibold">
                            <?php echo htmlspecialchars($row['reference_number']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 group-hover:font-semibold client-name">
                            <?php echo htmlspecialchars($row['clientname']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 group-hover:font-semibold">
                            <?php echo htmlspecialchars($row['nameproject']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($row['step10_done'] > 0): ?>
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                    Complete
                                </span>
                            <?php else: ?>
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                    Incomplete
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-sm">
                            <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="<?php echo $clientLink; ?>" onclick="event.stopPropagation();" class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-md shadow-sm transition duration-150">
                                Update
                            </a>
                        </td>
                    </tr>
                <?php endwhile;
            else: ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
    <!-- JavaScript for Search -->
    <script>

fetch('get_client_data.php')
  .then(response => response.json())
  .then(data => {
    if (data.error) {
      console.error('Error:', data.error);
      return;
    }

    const totalClients = data.newClientCount + data.oldClientCount;
    document.getElementById('totalClientsNumber').textContent = totalClients;

    // PIE: New vs Old Clients
    const ctxType = document.getElementById('combinedClientChart').getContext('2d');
    new Chart(ctxType, {
      type: 'pie',
      data: {
        labels: ['New Clients', 'Old Clients'],
        datasets: [{
          data: [data.newClientCount, data.oldClientCount],
          backgroundColor: ['#4CAF50', '#2196F3'],
          borderColor: ['#fff', '#fff'],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' }
        }
      }
    });

    // PIE: Completed vs Incomplete Clients
    const ctxStatus = document.getElementById('statusClientChart').getContext('2d');
    const totalStatus = data.completedClients + data.incompleteClients;
    new Chart(ctxStatus, {
      type: 'pie',
      data: {
        labels: ['Completed', 'Incomplete'],
        datasets: [{
          data: [data.completedClients, data.incompleteClients],
          backgroundColor: ['#10B981', '#EF4444'],
          borderColor: ['#fff', '#fff'],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' },
          tooltip: {
            callbacks: {
              label: function (context) {
                const value = context.raw;
                const percent = ((value / totalStatus) * 100).toFixed(1);
                return `${context.label}: ${value} (${percent}%)`;
              }
            }
          }
        }
      }
    });
  })
  .catch(error => {
    console.error('Fetch error:', error);
  });



        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');

        function performSearch() {
            const filter = searchInput.value.trim().toLowerCase();
            const rows = document.querySelectorAll('.client-row'); // Use the class name for rows

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let rowContainsSearch = false; // Flag to check if row matches the search

                // Loop through all columns in the row
                cells.forEach(cell => {
                    const cellText = cell.textContent.trim().toLowerCase();
                    if (cellText.includes(filter)) {
                        rowContainsSearch = true;
                    }
                });

                // Show or hide the row based on search result
                if (filter === '' || rowContainsSearch) {
                    row.style.display = ''; // Show row if it matches
                } else {
                    row.style.display = 'none'; // Hide row if it doesn't match
                }
            });
        }

        // Trigger on search button click
        searchBtn.addEventListener('click', performSearch);

        // Trigger on Enter key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submission if inside form
                performSearch();
            }
        });

        // Sorting functionality
        const sortSelect = document.getElementById('sortOrder');
        const clientTableBody = document.getElementById('clientTableBody');
        const clientRows = Array.from(clientTableBody.querySelectorAll('.client-row'));

        // Function to sort the rows
        function sortTable() {
            const sortOrder = sortSelect.value;
            const sortedRows = clientRows.sort((rowA, rowB) => {
                const nameA = rowA.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const nameB = rowB.querySelector('td:nth-child(2)').textContent.toLowerCase();

                if (sortOrder === 'asc') {
                    return nameA.localeCompare(nameB);
                } else {
                    return nameB.localeCompare(nameA);
                }
            });

            // Reattach the sorted rows to the table body
            sortedRows.forEach(row => clientTableBody.appendChild(row));
        }

        // Trigger sorting whenever the sort option changes
        sortSelect.addEventListener('change', sortTable);

        // Call the sorting function initially to apply the default order (A to Z)
        sortTable();
    </script>

</body>

</html>