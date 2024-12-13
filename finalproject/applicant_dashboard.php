<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: login.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard</title>
    <link rel="stylesheet" href="applicant_dashboard.css"> <!-- Linking the CSS file -->
</head>
<body>
    <?php include 'applicant_nbar.php'; ?>
    
    <header>
        <h1>Welcome, Applicant <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    </header>

    <div class="main-container">
        <h2>Your Dashboard</h2>
        
        <nav>
            <form action="view_jobs.php" method="GET">
                <button type="submit">View Available Job Posts</button>
            </form>

            <form action="view_applied_jobs.php" method="GET">
                <button type="submit">View Applied Jobs</button>
            </form>

            <form action="applicant_messages.php" method="GET">
                <button type="submit">Messages</button>
            </form>
        </nav>
    </div>
</body>
</html>
