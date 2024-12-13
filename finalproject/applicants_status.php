<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'HR') {
    header("Location: login.php");
    exit;
}

// Fetch accepted applicants
$query = "SELECT u.username, a.status, a.created_at, j.title AS job_title
          FROM applications a
          JOIN users u ON a.applicant_id = u.id
          JOIN job_posts j ON a.job_id = j.id
          WHERE a.status = 'Accepted'";

$acceptedApplicants = $pdo->query($query)->fetchAll();

// Fetch rejected applicants
$query = "SELECT u.username, a.status, a.created_at, j.title AS job_title
          FROM applications a
          JOIN users u ON a.applicant_id = u.id
          JOIN job_posts j ON a.job_id = j.id
          WHERE a.status = 'Rejected'";

$rejectedApplicants = $pdo->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants Status</title>
    <link rel="stylesheet" href="applicants_status.css">
</head>
<body>
    <?php include 'hr_nbar.php'; ?>

    <header>
        <h1>Applicants Status</h1>
    </header>

    <div class="container">
        <h2>Accepted Applicants</h2>
        <?php if (!empty($acceptedApplicants)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Applicant Name</th>
                        <th>Job Title</th>
                        <th>Application Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($acceptedApplicants as $applicant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($applicant['username']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No accepted applicants found.</p>
        <?php endif; ?>

        <h2>Rejected Applicants</h2>
        <?php if (!empty($rejectedApplicants)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Applicant Name</th>
                        <th>Job Title</th>
                        <th>Application Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rejectedApplicants as $applicant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($applicant['username']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No rejected applicants found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
