<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password
$dbname = "podcast_comments";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create tables if they don't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating users table: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS podcasts (
    podcast_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    publish_date DATETIME,
    buzzsprout_id VARCHAR(100)
)";

if (!$conn->query($sql)) {
    die("Error creating podcasts table: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    podcast_id INT,
    user_id INT,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (podcast_id) REFERENCES podcasts(podcast_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
)";

if (!$conn->query($sql)) {
    die("Error creating comments table: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS ratings (
    rating_id INT AUTO_INCREMENT PRIMARY KEY,
    podcast_id INT,
    user_id INT,
    rating_value TINYINT NOT NULL CHECK (rating_value BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (podcast_id) REFERENCES podcasts(podcast_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    UNIQUE KEY unique_rating (podcast_id, user_id)
)";

if (!$conn->query($sql)) {
    die("Error creating ratings table: " . $conn->error);
}

// Insert a default podcast if none exists
$sql = "INSERT IGNORE INTO podcasts (podcast_id, title, description, buzzsprout_id) 
        VALUES (1, 'Kenyan Weather & News', 'Daily weather updates and news from Kenya', '2455981')";
$conn->query($sql);

// Insert a default user if none exists (for demo purposes)
$sql = "INSERT IGNORE INTO users (user_id, username, email) 
        VALUES (1, 'Guest', 'guest@example.com')";
$conn->query($sql);
?>