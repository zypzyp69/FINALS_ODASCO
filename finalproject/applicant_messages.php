<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message_body = $_POST['message'];
    $receiver_id = $_POST['hr_id'];


    $messageStatus = sendMessageToHR($pdo, $_SESSION['user_id'], $receiver_id, $message_body);

    if ($messageStatus) {
        $_SESSION['message'] = "Your message has been sent successfully!";
        header("Location: view_messages.php");
        exit;
    } else {
        $_SESSION['message'] = "There was an error sending your message.";
    }
}

$hrList = getHRList($pdo);  
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message HR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea {
            resize: none;
        }

        button {
            background-color: #0a74da;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        button:hover {
            background-color: #005bb5;
            transform: translateY(-2px);
        }

        a {
            text-decoration: none;
        }

        a button {
            margin-top: 10px;
            background-color: #0a74da; 
            color: white;
        }

        a button:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>
    <?php include 'applicant_nbar.php'; ?>
    <div class="container">
        <h1>Message HR</h1>

        <form action="applicant_messages.php" method="POST">
            <label for="hr_id">Select HR Representative:</label>
            <select name="hr_id" id="hr_id" required>
                <?php foreach ($hrList as $hr): ?>
                    <option value="<?php echo $hr['id']; ?>">
                        <?php echo htmlspecialchars($hr['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="message">Your Message:</label>
            <textarea name="message" id="message" rows="4" required></textarea>

            <button type="submit">Send Message</button>
        </form>

        <a href="view_messages.php">
            <button>View Messages</button>
        </a>
    </div>
</body>
</html>
