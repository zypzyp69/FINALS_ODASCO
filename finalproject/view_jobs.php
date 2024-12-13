<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: login.php");
    exit;
}



$jobs = getAllJobPosts($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link rel="stylesheet" href="view_jobs.css">
</head>
<body>
    <?php include 'applicant_nbar.php'; ?>
    
    <div class="container">
        <h1>Available Job Posts</h1>


        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert">
                <?php 
                    echo htmlspecialchars($_SESSION['message']);
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (empty($jobs)): ?>
            <p>No job posts available at the moment.</p>
        <?php else: ?>
            <ul class="job-list">
                <?php foreach ($jobs as $job): ?>
                    <li class="job-item">
                        <strong><?php echo htmlspecialchars($job['title']); ?></strong><br>
                        <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                        <p><strong>Posted by:</strong> <?php echo htmlspecialchars($job['hr_username']); ?></p>
                        <a class="apply-btn" href="apply_job.php?job_id=<?php echo $job['id']; ?>">Apply</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</body>
</html>
