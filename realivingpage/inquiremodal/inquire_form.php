<?php
include '../../connection/connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    $created_at = date('Y-m-d H:i:s');

    // Check kung tama ang pangalan ng table sa database mo (adjust kung kailangan)
    $sql = "INSERT INTO contact_inquiries (full_name, phone_number, email, message, created_at, sent_to_admin, sent_at)
            VALUES (?, ?, ?, ?, ?, 0, NULL)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $phone, $email, $message, $created_at);

    if ($stmt->execute()) {
        echo "<script>window.location.href='inquire_form.php?success=1';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inquire Form</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

  <style>
  .hide-scrollbar::-webkit-scrollbar {
    display: none;
  }
  .hide-scrollbar {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
  }
</style>

</head>


<body class="p-5 bg-white font-[crimson] overflow-hidden h-screen">

<?php if (isset($_GET['success'])): ?>
  <div class="fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 animate-bounce transition-all duration-500">
    <i class="fas fa-check-circle mr-2"></i> Inquiry submitted successfully!
  </div>
  <script>
    setTimeout(() => {
      document.querySelector('.fixed').style.display = 'none';
    }, 3000); // auto-hide after 3 seconds
  </script>
<?php endif; ?>


  <form action="inquire_form.php" method="POST"
    class="space-y-4 overflow-y-auto max-h-[90vh] hide-scrollbar">
    
    <h2 class="text-2xl font-bold text-orange-900 text-center mb-6">Inquire Now</h2>

    <div class="relative">
      <label class="block mb-1 font-medium text-sm text-gray-700">Full Name</label>
      <i class="fas fa-user absolute left-3 top-9 text-gray-400"></i>
      <input type="text" name="name" required
        class="w-full border rounded px-10 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"  placeholder="Enter your name">
    </div>

    <div class="relative">
      <label class="block mb-1 font-medium text-sm text-gray-700">Email</label>
      <i class="fas fa-envelope absolute left-3 top-9 text-gray-400"></i>
      <input type="email" name="email" required
        class="w-full border rounded px-10 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"  placeholder="your@gmail.com"> 
    </div>

    <div class="relative">
      <label class="block mb-1 font-medium text-sm text-gray-700">Contact Number</label>
      <i class="fas fa-phone absolute left-3 top-9 text-gray-400"></i>
      <input type="tel" name="phone"
        class="w-full border rounded px-10 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"  placeholder="+63 xxx xxx xxxx">
    </div>

    <div class="relative">
      <label class="block mb-1 font-medium text-sm text-gray-700">Message</label>
      <i class="fas fa-comment-dots absolute left-3 top-9 text-gray-400"></i>
      <textarea name="message" rows="4" required
        class="w-full border rounded px-10 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Tell us about your interior design project..."></textarea>
    </div>

    <div class="text-right">
      <button type="submit"
        class="bg-orange-900 text-white px-6 py-2 rounded hover:bg-orange-700 transition-all duration-300">
        <i class="fas fa-paper-plane mr-2"></i>Submit
      </button>
    </div>

  </form>
</body>

</html>
