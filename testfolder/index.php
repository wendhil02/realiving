<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Chat with Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <form action="chat.php" method="GET" class="bg-white p-6 rounded shadow w-96 space-y-4">
    <h2 class="text-xl font-bold text-center">Enter Reference Number</h2>
    <input type="text" name="ref" required placeholder="Reference Number"
           class="w-full border p-2 rounded" />
    <button type="submit"
            class="bg-blue-600 text-white w-full py-2 rounded hover:bg-blue-700">
      Open Chat
    </button>
  </form>
</body>
</html>
