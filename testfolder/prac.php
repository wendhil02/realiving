<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inquire</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
  <div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Send an Inquiry</h2>
    <form action="submit_inquiry.php" method="POST" class="space-y-4">
      <input name="name" type="text" placeholder="Your Name" required class="w-full border p-2 rounded"/>
      <input name="email" type="email" placeholder="Your Email" required class="w-full border p-2 rounded"/>
      <textarea name="message" placeholder="Your Message" required class="w-full border p-2 rounded h-32"></textarea>
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Send</button>
    </form>
  </div>
</body>
</html>
