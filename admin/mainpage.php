<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    // Redirect to login page if not logged in
    header("Location: ../index.php");
    exit();
}
include 'design/mainbody.php';
include '../connection/connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>

<body class="bg-gray-200">

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-center bg-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-center w-full max-w-7xl space-y-8 md:space-y-0 md:space-x-8 p-4">
                <div class="relative flex w-full md:w-[1000px] h-[450px] items-center justify-center overflow-hidden bg-cover bg-center" id="bg-slide-container">
                    <!-- Background Image is handled via JS -->

                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gray-700 bg-opacity-20 backdrop-blur-sm "></div>

                    <!-- Slide Content -->
                    <div id="slide-container" class="absolute inset-0 flex flex-col items-center justify-center text-center px-10 transition-opacity duration-1000 opacity-100">
                        <h1 class="text-white text-6xl font-bold mb-6" id="title-text"></h1>
                        <p class="text-white text-xl" id="subtitle-text"></p>
                    </div>
                </div>
            </div>
        </div>


        <div class="flex justify-center">
            <div class="mb-6 w-full max-w-md mt-4">
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


        <!-- Client Records Table -->
       <div class="bg-white shadow-md rounded p-6 w-full overflow-x-auto">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Client Records</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference No.</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Updated</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php
            $result = $conn->query("SELECT * FROM user_info ORDER BY created_at DESC");
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $clientLink = 'client_update.php?id=' . urlencode($row['id']);
            ?>
                    <tr onclick="window.location.href='<?php echo $clientLink; ?>'"
                        class="hover:bg-gray-100 cursor-pointer transition duration-200 client-row">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <?php echo htmlspecialchars($row['reference_number']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <?php echo htmlspecialchars($row['clientname']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $row['created_at']; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                            <a href="<?php echo $clientLink; ?>" onclick="event.stopPropagation();"
                                class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs px-4 py-2 rounded-md transition duration-200">
                                Update
                            </a>
                        </td>
                    </tr>
                <?php endwhile;
            else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

    </main>



    <!-- JavaScript for Search -->
    <script>
        const slides = [{
                title: `<img src="../logo/new.png" alt="Noble Home Logo" class="h-[150px] object-contain" />`,
                subtitle: "",
                bgImage: "url('../logo/newbackone.png')"
            },
            {
                title: `<img src="../logo/mmone.png" alt="Real Living Logo" class="h-[150px] object-contain" />`,
                subtitle: "",
                bgImage: "url('../logo/real.png')"
            }
        ];


        let currentSlide = 0;
        const titleText = document.getElementById('title-text');
        const subtitleText = document.getElementById('subtitle-text');
        const slideContainer = document.getElementById('slide-container');
        const bgSlideContainer = document.getElementById('bg-slide-container');

        // Function to update slide
        function updateSlide() {
            titleText.innerHTML = slides[currentSlide].title;
            subtitleText.textContent = slides[currentSlide].subtitle;
            bgSlideContainer.style.backgroundImage = slides[currentSlide].bgImage;
        }

        // Initialize first slide
        updateSlide();
        bgSlideContainer.style.backgroundSize = 'cover';
        bgSlideContainer.style.backgroundPosition = 'center';

        // Change slide every 4 seconds
        setInterval(() => {
            slideContainer.classList.add('opacity-0');

            setTimeout(() => {
                currentSlide = (currentSlide + 1) % slides.length;
                updateSlide();
                slideContainer.classList.remove('opacity-0');
            }, 500);
        }, 4000);

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
    </script>

</body>

</html>