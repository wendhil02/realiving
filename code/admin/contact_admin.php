<?php
include '../database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Handle message deletion FIRST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $sql = "DELETE FROM contact WHERE id = $delete_id";
    if ($conn->query($sql)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// Handle reply submission
if (isset($_POST['send_reply'])) {
    $to = $_POST['to_email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        // SMTP Setup
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'arme.jimenez.sjc@phinmaed.com';
        $mail->Password = 'aghx clrc xqmu umqp';  
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('arme.jimenez.sjc@phinmaed.com', 'I.T man');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br($message);

        $mail->send();
        echo "<script>alert('Reply sent successfully.');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Reply failed. Mailer Error: {$mail->ErrorInfo}');</script>";
    }
}

// Load message details
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM contact WHERE id = $id";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        echo "<div class='message-detail'>";
        echo "<h2>Message Details</h2>";
        echo "<p><strong>Name:</strong> {$row['name']}</p>";
        echo "<p><strong>Phone:</strong> {$row['phone']}</p>";
        echo "<p><strong>Email:</strong> {$row['email']}</p>";
        echo "<p><strong>Subject:</strong> {$row['subject']}</p>";
        echo "<p><strong>Message:</strong><br>{$row['message']}</p>";
        echo "<hr>";

        // Reply form
        echo "<h3>Reply to {$row['name']}</h3>";
        echo "<form method='post' action='contact_admin.php' style='margin-top: 15px;'>
                <input type='hidden' name='to_email' value='{$row['email']}'>
                <input type='hidden' name='id' value='{$row['id']}'>
                <label for='subject'><strong>Subject:</strong></label><br>
                <input type='text' name='subject' id='subject' required style='width:100%; padding:8px; margin-bottom:10px;'><br>
                <label for='message'><strong>Message:</strong></label><br>
                <textarea name='message' id='message' rows='5' required style='width:100%; padding:8px;'></textarea><br>
                <button type='submit' name='send_reply' style='margin-top:10px;'>Send Reply</button>
              </form>";
        echo "</div>";
    } else {
        echo "<p>Message not found.</p>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Contact Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="sidebar">
    <h3>Messages</h3>
    <?php
    $sql = "SELECT id, name, subject FROM contact ORDER BY id DESC";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<div class='message-item' onclick='loadMessage({$row['id']})'>
                <strong>{$row['name']}</strong><br>{$row['subject']}
                <span class='delete-icon' onclick='event.stopPropagation(); deleteMessage({$row['id']})'>
                    <i class='fas fa-trash-alt'></i>
                </span>
              </div>";
    }
    ?>
</div>

<div class="content" id="messageDetails">
    <h2>Select a message to view details</h2>
</div>

<script>
function loadMessage(id) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        document.getElementById("messageDetails").innerHTML = this.responseText;
    }
    xhttp.open("GET", "contact_admin.php?id=" + id, true);
    xhttp.send();
}

function deleteMessage(id) {
    if (confirm("Are you sure you want to delete this message?")) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            if (this.responseText.trim() === "success") {
                location.reload();
            } else {
                alert("Failed to delete message.");
            }
        };
        xhttp.open("POST", "contact_admin.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("delete_id=" + id);
    }
}
</script>

</body>
</html>

<!--  -->

<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    height: 100vh;
    background-color: #f4f6f8;
    color: #333;
  }

  .sidebar {
    width: 30%;
    background-color: #fff;
    border-right: 1px solid #ddd;
    padding: 20px;
    overflow-y: auto;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
  }

  .sidebar h3 {
    margin-bottom: 20px;
    font-size: 1.4rem;
    color: #444;
  }

  .message-item {
    background-color: #f9f9f9;
    border: 1px solid #e0e0e0;
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    transition: background 0.3s, box-shadow 0.3s;
    cursor: pointer;
  }

  .message-item:hover {
    background-color: #eef2f5;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  }

  .message-item strong {
    font-weight: 600;
    font-size: 1rem;
    display: block;
    margin-bottom: 5px;
  }

  .content {
    flex: 1;
    padding: 40px;
    overflow-y: auto;
    background-color: #fafafa;
  }

  #messageDetails h2 {
    font-size: 1.8rem;
    margin-bottom: 20px;
  }

  #messageDetails p {
    margin-bottom: 15px;
    line-height: 1.6;
  }

  #messageDetails strong {
    color: #555;
  }
    </style>

<style>
        body {
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            margin: 0;
            height: 100vh;
            background-color: #f7f9fb;
        }
        .sidebar {
            width: 30%;
            border-right: 1px solid #ccc;
            padding: 20px;
            overflow-y: auto;
            background: #ffffff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        .sidebar h3 {
            margin-bottom: 20px;
        }
        .message-item {
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            position: relative;
            transition: background 0.2s;
        }
        .message-item:hover {
            background: #f0f4ff;
        }
        .delete-icon {
            position: absolute;
            right: 15px;
            top: 12px;
            color: #999;
            cursor: pointer;
            transition: color 0.2s, transform 0.2s;
        }
        .delete-icon:hover {
            color: #d33;
            transform: scale(1.2);
        }
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        .message-detail {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .message-detail h2 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .message-detail p {
            margin: 10px 0;
        }
    </style>
