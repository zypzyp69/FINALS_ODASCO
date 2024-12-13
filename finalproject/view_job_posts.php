<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'HR') {
    header("Location: login.php");
    exit;
}

$hrId = $_SESSION['user_id'];

$jobPosts = getJobPostsByHR($pdo, $hrId);


if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Job Posts</title>
    <link rel="stylesheet" href="view_job_posts.css">
</head>
<style>
    .message {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
        border-radius: 5px;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
    }
</style>
<body>
    <?php include 'hr_nbar.php'; ?>

    <header>
        <h1>My Job Posts</h1>
    </header>

    <main>
        <?php if (isset($message)): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($jobPosts)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Qualifications</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobPosts as $post): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo htmlspecialchars($post['description']); ?></td>
                            <td><?php echo htmlspecialchars($post['qualifications']); ?></td>
                            <td><?php echo htmlspecialchars($post['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No job posts found. Create a new one to get started!</p>
        <?php endif; ?>
        
        <a href="add_job_post.php"><button>Create New Job Post</button></a>
    </main>
</body>
</html>
