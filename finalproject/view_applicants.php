<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'HR') {
    header("Location: login.php");
    exit;
}


$applications = getAllApplications($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>
    <link rel="stylesheet" href="view_applicants.css"> 
</head>
<body>
    <?php include 'hr_nbar.php'; ?>
    <header>
        <h1>Applicants</h1>
    </header>
    <ul>
        <?php foreach ($applications as $app): ?>
            <li>
                <strong>Applicant:</strong> <?php echo htmlspecialchars($app['applicant_name']); ?><br>
                <strong>Job Title:</strong> <?php echo htmlspecialchars($app['job_title']); ?><br>
                <strong>Status:</strong> <?php echo htmlspecialchars($app['status']); ?><br>


                <strong>Resume:</strong> 
                <a href="http://localhost/finalproject/<?php echo htmlspecialchars($app['resume_path']); ?>" target="_blank" download>Download Resume</a><br>

                <form method="POST" action="core/handleForms.php">
                    <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                    <textarea name="response_message" placeholder="Response Message" required></textarea><br>
                    <button type="submit" name="acceptApplicationBtn">Accept</button>
                    <button type="submit" name="rejectApplicationBtn">Reject</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
