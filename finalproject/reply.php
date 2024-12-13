<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}



if (isset($_GET['message_id'])) {
    $message_id = $_GET['message_id'];
    $message = getMessageById($pdo, $message_id);
} else {
    header("Location: view_messages.php"); 
    exit;
}


if ($_SESSION['role'] === 'HR' && $message['receiver_id'] === $_SESSION['user_id']) {

    $receiver_id = $message['sender_id']; 
    $role = 'HR';
} elseif ($_SESSION['role'] === 'Applicant' && $message['receiver_id'] === $_SESSION['user_id']) {

    $receiver_id = $message['sender_id']; 
    $role = 'Applicant';
} else {

    header("Location: view_messages.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message_body = $_POST['message'];


    $messageStatus = sendMessageToHR($pdo, $_SESSION['user_id'], $receiver_id, $message_body);

    if ($messageStatus) {
        $_SESSION['message'] = "Your reply has been sent successfully!";
        header("Location: view_messages.php");
        exit;
    } else {
        $_SESSION['message'] = "There was an error sending your reply.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Message</title>
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

        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
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


        h3 {
            font-size: 22px;
            margin-top: 40px;
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
            line-height: 1.5;
        }


        strong {
            color: #333;
        }
    </style>
</head>
<body>
    <?php
    if ($_SESSION['role'] === 'HR') {
        include 'hr_nbar.php';
    } else {
        include 'applicant_nbar.php';
    }
    ?>

    <div class="container">
        <h1>Reply to Message</h1>

        <form action="reply.php?message_id=<?php echo $message_id; ?>" method="POST">
            <label for="message">Your Reply:</label><br>
            <textarea name="message" id="message" rows="4" required></textarea><br><br>

            <button type="submit">Send Reply</button>
        </form>

        <h3>Original Message</h3>
        <p><strong>From:</strong> <?php echo htmlspecialchars($message['sender_name']); ?></p>
        <p><strong>Message:</strong> <?php echo htmlspecialchars($message['message_body']); ?></p>
    </div>
</body>
</html>
