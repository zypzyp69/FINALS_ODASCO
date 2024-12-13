<?php  


function insertNewUser($pdo, $username, $password, $role) {

    $checkUserSql = "SELECT * FROM users WHERE username = ?";
    $checkUserSqlStmt = $pdo->prepare($checkUserSql);
    $checkUserSqlStmt->execute([$username]);

    if ($checkUserSqlStmt->rowCount() == 0) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $executeQuery = $stmt->execute([$username, $hashedPassword, $role]);

        if ($executeQuery) {
            $_SESSION['message'] = "User successfully registered";
            return true;
        } else {
            $_SESSION['message'] = "An error occurred during registration";
            return false;
        }
    } else {
        $_SESSION['message'] = "Username already exists";
        return false;
    }
}


function loginUser($pdo, $username, $password) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashedPassword = $user['password'];
        $role = $user['role'];

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $role;
            $_SESSION['user_id'] = $user['id'];
            return true;
        } else {
            $_SESSION['message'] = "Invalid password. Please try again.";
            return false;
        }
    } else {
        $_SESSION['message'] = "Username does not exist. Please register.";
        return false;
    }
}

function getJobPostsByHR($pdo, $hrId) {
    $sql = "SELECT * FROM job_posts WHERE created_by = ? ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hrId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




function getAllApplications($pdo) {
    $sql = "SELECT a.id, a.status, a.created_at, 
                   u.username AS applicant_name, 
                   a.resume_path,  
                   j.title AS job_title 
            FROM applications a 
            JOIN users u ON a.applicant_id = u.id 
            JOIN job_posts j ON a.job_id = j.id 
            ORDER BY a.created_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




function addJobPost($pdo, $title, $description, $qualifications, $created_by) {
    $sql = "INSERT INTO job_posts (title, description, qualifications, created_by) 
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$title, $description, $qualifications, $created_by]);

    return $result;
}


function getAllJobPosts($pdo) {
    $sql = "SELECT jp.*, u.username AS hr_username FROM job_posts jp 
            JOIN users u ON jp.created_by = u.id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function getJobPostById($pdo, $job_id) {
    $sql = "SELECT * FROM job_posts WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$job_id]);
    return $stmt->fetch();
}


function applyToJob($pdo, $applicant_id, $job_id, $resume_path, $cover_letter) {
    $sql = "INSERT INTO applications (job_id, applicant_id, resume_path, cover_letter) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$job_id, $applicant_id, $resume_path, $cover_letter]);
}


function sendMessageToHR($pdo, $sender_id, $receiver_id, $message_body) {
    $sql = "INSERT INTO messages (sender_id, receiver_id, message_body) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$sender_id, $receiver_id, $message_body]);
}


function getHRList($pdo) {
    $sql = "SELECT id, username FROM users WHERE role = 'HR'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getAppliedJobs($pdo, $applicant_id) {
    $sql = "
        SELECT jp.title, jp.description, a.status, a.created_at AS applied_on, a.resume_path, a.cover_letter
        FROM applications a
        JOIN job_posts jp ON a.job_id = jp.id
        WHERE a.applicant_id = :applicant_id
        ORDER BY a.created_at DESC
    ";


    $stmt = $pdo->prepare($sql);
    $stmt->execute(['applicant_id' => $applicant_id]);


    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getApplicantList($pdo) {
    $sql = "SELECT id, username FROM users WHERE role = 'Applicant'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMessageById($pdo, $message_id) {
    $sql = "SELECT m.id, m.sender_id, m.receiver_id, m.message_body, m.created_at, 
                   u.username AS sender_name 
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$message_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function getMessagesForApplicant($pdo, $applicant_id) {
    $sql = "SELECT m.*, u.username AS sender_name 
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.receiver_id = ? 
            ORDER BY m.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$applicant_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMessagesForHR($pdo, $hr_id) {
    $sql = "SELECT m.*, u.username AS sender_name 
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.receiver_id = ? 
            ORDER BY m.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hr_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getUsersByRole($pdo) {
    $sql = "SELECT id, username, role FROM users WHERE role IN ('Applicant', 'HR')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserByUsername($pdo, $username) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


?>