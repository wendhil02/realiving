
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
  