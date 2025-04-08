<?php
session_start();
require_once 'config.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from database
$email = $_SESSION['email'];
$userQuery = $conn->query("SELECT * FROM users WHERE email = '$email'");
$user = $userQuery->fetch_assoc();

// If user not found (unlikely but possible if account was deleted)
if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .profile-header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .profile-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .detail-group {
            margin-bottom: 15px;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }
        
        .detail-value {
            padding: 12px;
            background: #f5f5f5;
            border-radius: 7px;
            color: #333;
        }
        
        .profile-actions {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .profile-actions button {
            width: auto;
            padding: 10px 20px;
        }
        
        @media (max-width: 768px) {
            .profile-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>My Profile</h1>
            <p>View and manage your account details</p>
        </div>
        
        <div class="profile-details">
            <div class="detail-group">
                <span class="detail-label">Full Name</span>
                <div class="detail-value"><?= htmlspecialchars($user['name']); ?></div>
            </div>
            
            <div class="detail-group">
                <span class="detail-label">Email Address</span>
                <div class="detail-value"><?= htmlspecialchars($user['email']); ?></div>
            </div>
            
            <div class="detail-group">
                <span class="detail-label">Username</span>
                <div class="detail-value"><?= htmlspecialchars($user['username']); ?></div>
            </div>
            
            <div class="detail-group">
                <span class="detail-label">Phone Number</span>
                <div class="detail-value"><?= htmlspecialchars($user['phone']); ?></div>
            </div>
            
            <div class="detail-group">
                <span class="detail-label">Gender</span>
                <div class="detail-value"><?= ucfirst(htmlspecialchars($user['gender'])); ?></div>
            </div>
            
            <div class="detail-group">
                <span class="detail-label">County</span>
                <div class="detail-value"><?= htmlspecialchars($user['county']); ?></div>
            </div>
        </div>
        
        <div class="profile-actions">
            <button onclick="window.location.href='index.html'">Back to Home</button>
            <button onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>
</body>
</html>
