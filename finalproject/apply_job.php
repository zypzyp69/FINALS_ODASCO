<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: login.php");
    exit;
}



if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $job = getJobPostById($pdo, $job_id); 
} else {
    header("Location: view_jobs.php"); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['resume'])) {
    $cover_letter = $_POST['cover_letter'];
    $resume = $_FILES['resume'];

    if ($resume['type'] == 'application/pdf' && $resume['error'] == 0) {
        $resume_folder = 'resumes/';

        if (!is_dir($resume_folder)) {
            mkdir($resume_folder, 0777, true);
        }

        $resume_filename = uniqid() . '-' . basename($resume['name']);
        $resume_path = $resume_folder . $resume_filename;

        move_uploaded_file($resume['tmp_name'], $resume_path);

        $applicationStatus = applyToJob($pdo, $_SESSION['user_id'], $job_id, $resume_path, $cover_letter);

        if ($applicationStatus) {
            $_SESSION['message'] = "Your application has been submitted successfully!";
            header("Location: view_jobs.php");
            exit;
        } else {
            $_SESSION['message'] = "There was an error while submitting your application.";
        }
    } else {
        $_SESSION['message'] = "Please upload a valid PDF resume.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <link rel="stylesheet" href="apply_job.css">
</head>
<body>
    <?php include 'applicant_nbar.php'; ?>
    <div class="container">
        <h1>Apply for Job: <?php echo htmlspecialchars($job['title']); ?></h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert">
                <?php 
                    echo htmlspecialchars($_SESSION['message']);
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <form action="apply_job.php?job_id=<?php echo $job_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="cover_letter">Cover Letter:</label><br>
                <textarea name="cover_letter" id="cover_letter" rows="4" required></textarea><br>
            </div>

            <div class="form-group">
                <label for="resume">Upload Resume (PDF only):</label><br>
                <input type="file" name="resume" accept="application/pdf" required><br><br>
            </div>

            <button type="submit" class="btn-primary">Submit Application</button>
        </form>

        <a href="view_jobs.php" class="back-link">Back to Job List</a>
    </div>
</body>
</html>
