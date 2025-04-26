<?php


include 'design/mainbody.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body class="bg-gray-50">

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <form action="insert_news.php" method="POST" enctype="multipart/form-data" class="space-y-4">
  <input type="text" name="title" placeholder="News Title" required class="border p-2 w-full">
  <textarea name="summary" placeholder="Summary" required class="border p-2 w-full"></textarea>
  <input type="file" name="image" accept="image/*" required class="border p-2 w-full">
  <input type="text" name="link" placeholder="Link (e.g. news1.php)" required class="border p-2 w-full">
  <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Insert News</button>
</form>

    </main>

</body>
</html>