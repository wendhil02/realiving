<?php
session_start();

include '../design/mainbody.php';
include '../checkrole.php';
include '../../connection/connection.php';

// Allow only admin1 to admin5
require_role(['admin1', 'admin2', 'admin3', 'admin4', 'admin5', 'superadmin']);

// Fetch inquiries
$inquiries = $conn->query("SELECT * FROM contact_inquiries ORDER BY created_at DESC");

// Fetch admin emails, client_status, and role
$admins = $conn->query("SELECT id, email, client_status, role FROM account WHERE role LIKE 'admin%'");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Client Management Dashboard</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Google Fonts: Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#eff6ff',
              100: '#dbeafe',
              200: '#bfdbfe',
              300: '#93c5fd',
              400: '#60a5fa',
              500: '#3b82f6',
              600: '#2563eb',
              700: '#1d4ed8',
              800: '#1e40af',
              900: '#1e3a8a',
            },
            realiving: {
              DEFAULT: '#FFCC00',
              light: '#FFED7D',
              dark: '#E6B800'
            },
            noblehome: {
              DEFAULT: '#FF7A00',
              light: '#FFA552',
              dark: '#E66D00'
            }
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          },
        }
      }
    }
  </script>

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    .chart-container {
      position: relative;
      height: 220px;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    /* Animation for notifications */
    @keyframes slideInNotification {
      from {
        transform: translateX(100%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    .notification-animate {
      animation: slideInNotification 0.3s ease forwards;
    }
  </style>
</head>

<body class="bg-slate-50 min-h-screen">

  <!-- Notification -->
  <?php if (isset($_SESSION['noti'])): ?>
    <div id="notifBox" class="fixed top-20 right-4 bg-white border-l-4 border-green-500 shadow-lg rounded-lg p-4 w-80 notification-animate z-50">
      <div class="flex items-center">
        <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
        <div>
          <p class="text-sm font-medium text-gray-900">Success</p>
          <p class="text-xs text-gray-600"><?= $_SESSION['noti']; ?></p>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-500">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <script>
      setTimeout(function() {
        var notif = document.getElementById("notifBox");
        if (notif) {
          notif.classList.add('opacity-0', 'transition-opacity', 'duration-300');
          setTimeout(() => notif.remove(), 300);
        }
      }, 3000);
    </script>
    <?php unset($_SESSION['noti']); ?>
  <?php endif; ?>

  <!-- Main Content -->
  <div class="pt-10 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    <!-- Dashboard Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Client Management Dashboard</h1>
      <p class="text-sm text-gray-600 mt-1">Overview of all client activities and statistics</p>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
      <!-- New vs Old Clients -->
      <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
        <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
          <i class="fas fa-users text-primary-500 mr-2"></i>
          Client Type Distribution
        </h2>
        <div class="chart-container">
          <canvas id="combinedClientChart"></canvas>
        </div>
      </div>

      <!-- Total Clients -->
      <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
        <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
          <i class="fas fa-chart-bar text-primary-500 mr-2"></i>
          Client Overview
        </h2>
        <div class="chart-container">
          <canvas id="totalClientsBar"></canvas>
        </div>
      </div>

      <!-- Completion Status -->
      <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
        <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
          <i class="fas fa-tasks text-primary-500 mr-2"></i>
          Completion Status
        </h2>
        <div class="chart-container">
          <canvas id="statusClientChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Search and Filters Section -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 mb-6">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Search Bar -->
        <div class="relative flex-grow max-w-md">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
          </div>
          <input type="text" id="searchInput" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5" placeholder="Search clients, projects...">
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-3">
          <!-- Sort Dropdown -->
          <div class="flex items-center">
            <label for="sortOrder" class="mr-2 text-sm font-medium text-gray-700">Sort by:</label>
            <select id="sortOrder" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5">
              <option value="asc">A to Z</option>
              <option value="desc">Z to A</option>
            </select>
          </div>


        </div>
      </div>
    </div>

    <!-- Client Records Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-5 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Client Records</h2>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
              <th class="px-6 py-4 font-medium">Reference No.</th>
              <th class="px-6 py-4 font-medium">Name</th>
              <th class="px-6 py-4 font-medium">Project Name</th>
              <th class="px-6 py-4 font-medium">Client Type</th>
              <th class="px-6 py-4 font-medium">Status</th>
              <th class="px-6 py-4 font-medium">Last Updated</th>
              <th class="px-6 py-4 font-medium text-right">Action</th>
            </tr>
          </thead>
          <tbody id="clientTableBody" class="divide-y divide-gray-200">
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
                <tr class="client-row hover:bg-gray-50 cursor-pointer transition-colors" onclick="window.location.href='<?php echo $clientLink; ?>'">
                  <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                    <?php echo htmlspecialchars($row['reference_number']); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap client-name">
                    <div class="flex items-center">
                      <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 mr-3">
                        <?php echo strtoupper(substr(htmlspecialchars($row['clientname']), 0, 1)); ?>
                      </div>
                      <span><?php echo htmlspecialchars($row['clientname']); ?></span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                    <?php echo htmlspecialchars($row['nameproject']); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <?php
                    $clientType = htmlspecialchars($row['client_type'] ?? 'N/A');

                    if (strtolower($clientType) === 'realiving') {
                      echo '<span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-medium text-yellow-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-yellow-500 mr-1"></span>
                                Realiving
                              </span>';
                    } elseif (strtolower($clientType) === 'noblehome') {
                      echo '<span class="inline-flex items-center rounded-full bg-orange-100 px-2.5 py-1 text-xs font-medium text-orange-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-orange-500 mr-1"></span>
                                Noblehome
                              </span>';
                    } else {
                      echo '<span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-gray-500 mr-1"></span>
                                ' . $clientType . '
                              </span>';
                    }
                    ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <?php if ($row['step10_done'] > 0): ?>
                      <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500 mr-1"></span>
                        Complete
                      </span>
                    <?php else: ?>
                      <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-800">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-500 mr-1"></span>
                        Incomplete
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-xs">
                    <div class="flex items-center">
                      <i class="far fa-calendar-alt mr-1.5"></i>
                      <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right">
                    <a href="<?php echo $clientLink; ?>" onclick="event.stopPropagation();" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-xs px-3 py-2 inline-flex items-center">
                      <i class="fas fa-pen-to-square mr-1.5"></i>
                      Update
                    </a>
                  </td>
                </tr>
              <?php
              endwhile;
            else:
              ?>
              <tr>
                <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                  <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-base">No records found</p>
                    <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->

    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-white py-4 border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <p class="text-center text-xs text-gray-500">© 2025 ClientFlow Management System. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // Fetch client data and create charts
    fetch('get_client_data.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          console.error('Error:', data.error);
          return;
        }

        // Chart 1: New vs Old Clients
        const ctxType = document.getElementById('combinedClientChart').getContext('2d');
        new Chart(ctxType, {
          type: 'doughnut',
          data: {
            labels: ['New Clients', 'Old Clients'],
            datasets: [{
              data: [data.newClientCount, data.oldClientCount],
              backgroundColor: ['#3b82f6', '#10b981'],
              borderColor: ['#ffffff', '#ffffff'],
              borderWidth: 2,
              hoverOffset: 15
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  usePointStyle: true,
                  padding: 15,
                  font: {
                    size: 12
                  }
                }
              },
              tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                  size: 14
                },
                bodyFont: {
                  size: 13
                },
                displayColors: false,
                callbacks: {
                  label: function(context) {
                    const label = context.label || '';
                    const value = context.parsed || 0;
                    const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                    const percentage = Math.round((value / total) * 100);
                    return `${label}: ${value} (${percentage}%)`;
                  }
                }
              }
            }
          }
        });

        const ctxTotal = document.getElementById('totalClientsBar').getContext('2d');
        new Chart(ctxTotal, {
          type: 'bar',
          data: {
            labels: ['Client Categories'],
            datasets: [{
                label: 'New Clients',
                data: [data.newClientCount],
                backgroundColor: '#3b82f6', // Blue
                borderRadius: 5,
                barThickness: 20
              },
              {
                label: 'Old Clients',
                data: [data.oldClientCount],
                backgroundColor: '#10b981', // Green
                borderRadius: 5,
                barThickness: 20
              },
              {
                label: 'Realiving',
                data: [data.realivingClientCount],
                backgroundColor: '#FFCC00', // Yellow
                borderRadius: 5,
                barThickness: 20
              },
              {
                label: 'Noblehome',
                data: [data.noblehomeClientCount],
                backgroundColor: '#FF7A00', // Orange
                borderRadius: 5,
                barThickness: 20
              },
              {
                label: 'Total Clients',
                data: [data.totalClientCount],
                backgroundColor: '#8B5CF6', // Purple
                borderRadius: 5,
                barThickness: 20
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              x: {
                display: false,
                grid: {
                  display: false
                }
              },
              y: {
                beginAtZero: true,
                ticks: {
                  precision: 0
                },
                grid: {
                  color: 'rgba(0, 0, 0, 0.05)'
                }
              }
            },
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  usePointStyle: true,
                  padding: 15,
                  boxWidth: 10,
                  font: {
                    size: 12
                  }
                }
              },
              tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                  size: 14
                },
                bodyFont: {
                  size: 13
                }
              }
            }
          }
        });


        // Chart 3: Completed vs Incomplete Clients
        const ctxStatus = document.getElementById('statusClientChart').getContext('2d');
        const totalStatus = data.completedClients + data.incompleteClients;
        new Chart(ctxStatus, {
          type: 'doughnut',
          data: {
            labels: ['Completed', 'Incomplete'],
            datasets: [{
              data: [data.completedClients, data.incompleteClients],
              backgroundColor: ['#10b981', '#ef4444'],
              borderColor: ['#ffffff', '#ffffff'],
              borderWidth: 2,
              hoverOffset: 15
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  usePointStyle: true,
                  padding: 15,
                  font: {
                    size: 12
                  }
                }
              },
              tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                  size: 14
                },
                bodyFont: {
                  size: 13
                },
                displayColors: false,
                callbacks: {
                  label: function(context) {
                    const value = context.parsed;
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

    document.addEventListener('DOMContentLoaded', () => {
      const searchInput = document.getElementById('searchInput');

      searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          performSearch();
        }
      });

      function performSearch() {
        const filter = searchInput.value.trim().toLowerCase();
        const rows = document.querySelectorAll('.client-row');

        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(filter) ? '' : 'none';
        });
      }
    });
    // Sorting functionality
    const sortSelect = document.getElementById('sortOrder');
    const clientTableBody = document.getElementById('clientTableBody');
    const clientRows = Array.from(clientTableBody.querySelectorAll('.client-row'));

    sortSelect.addEventListener('change', sortTable);

    function sortTable() {
      const sortOrder = sortSelect.value;
      const sortedRows = clientRows.sort((rowA, rowB) => {
        const nameA = rowA.querySelector('.client-name').textContent.trim().toLowerCase();
        const nameB = rowB.querySelector('.client-name').textContent.trim().toLowerCase();

        return sortOrder === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
      });

      sortedRows.forEach(row => clientTableBody.appendChild(row));
    }

    // Initial sort
    sortTable();
  </script>

</body>

</html>