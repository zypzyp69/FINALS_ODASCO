<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}



if ($_SESSION['role'] === 'Applicant') {
    $messages = getMessagesForApplicant($pdo, $_SESSION['user_id']);
} else if ($_SESSION['role'] === 'HR') {
    $messages = getMessagesForHR($pdo, $_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages</title>
    <link rel="stylesheet" href="view_messages.css">
</head>
<body>  
    <?php 
    if ($_SESSION['role'] === 'HR') {
        include 'hr_nbar.php'; 
    } elseif ($_SESSION['role'] === 'Applicant') {
        include 'applicant_nbar.php'; 
    }
    ?>
    
    <div class="container">
        <h1>Your Messages</h1>
        <ul>
            <?php foreach ($messages as $msg): ?>
                <li>
                    <strong>From:</strong> <?php echo htmlspecialchars($msg['sender_name']); ?><br>
                    <strong>Message:</strong> <?php echo htmlspecialchars($msg['message_body']); ?><br>
                    <strong>Sent On:</strong> <?php echo htmlspecialchars($msg['created_at']); ?><br>

                    <form action="reply.php" method="GET">
                        <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                        <button type="submit">Reply</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

