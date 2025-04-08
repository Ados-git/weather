<?php
header('Content-Type: application/json');
require_once 'pd_config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

class CommentSystem {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Add a new comment
    public function addComment($podcast_id, $username, $email, $comment_text) {
        // First, get or create user
        $user_id = $this->getOrCreateUser($username, $email);
        
        if (!$user_id) {
            return ['status' => 'error', 'message' => 'Failed to create user'];
        }

        // Insert comment
        $stmt = $this->conn->prepare("INSERT INTO comments (podcast_id, user_id, comment_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $podcast_id, $user_id, $comment_text);
        
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Comment added successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to add comment'];
        }
    }

    // Get or create user
    private function getOrCreateUser($username, $email) {
        // Check if user exists
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['user_id'];
        } else {
            // Create new user
            $stmt = $this->conn->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $email);
            
            if ($stmt->execute()) {
                return $stmt->insert_id;
            } else {
                return false;
            }
        }
    }

    // Update user information
    public function updateUser($username, $email) {
        $stmt = $this->conn->prepare("UPDATE users SET username = ? WHERE email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
    }

    // Get comments for a podcast
    public function getComments($podcast_id) {
        $stmt = $this->conn->prepare("
            SELECT c.*, u.username 
            FROM comments c
            JOIN users u ON c.user_id = u.user_id
            WHERE c.podcast_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->bind_param("i", $podcast_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        
        return $comments;
    }

    // Add or update a rating
    public function addRating($podcast_id, $rating_value) {
        // For simplicity, we're using a default user_id (1)
        // In a real application, you would use the logged-in user's ID
        $user_id = 1;
        
        // Check if rating exists
        $stmt = $this->conn->prepare("SELECT rating_id FROM ratings WHERE podcast_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $podcast_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing rating
            $stmt = $this->conn->prepare("UPDATE ratings SET rating_value = ? WHERE podcast_id = ? AND user_id = ?");
            $stmt->bind_param("iii", $rating_value, $podcast_id, $user_id);
        } else {
            // Insert new rating
            $stmt = $this->conn->prepare("INSERT INTO ratings (podcast_id, user_id, rating_value) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $podcast_id, $user_id, $rating_value);
        }
        
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Rating saved successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to save rating'];
        }
    }

    // Get average rating for a podcast
    public function getAverageRating($podcast_id) {
        $stmt = $this->conn->prepare("SELECT AVG(rating_value) as avg_rating, COUNT(*) as total_ratings FROM ratings WHERE podcast_id = ?");
        $stmt->bind_param("i", $podcast_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}

// Handle requests
$commentSystem = new CommentSystem($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_comment':
            $podcast_id = $_POST['podcast_id'] ?? 0;
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $comment_text = $_POST['comment_text'] ?? '';
            echo json_encode($commentSystem->addComment($podcast_id, $username, $email, $comment_text));
            break;
            
        case 'add_rating':
            $podcast_id = $_POST['podcast_id'] ?? 0;
            $rating_value = $_POST['rating_value'] ?? 0;
            echo json_encode($commentSystem->addRating($podcast_id, $rating_value));
            break;
            
        case 'update_user':
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $commentSystem->updateUser($username, $email);
            echo json_encode(['status' => 'success']);
            break;
            
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $podcast_id = $_GET['podcast_id'] ?? 0;
    
    if (isset($_GET['get_comments'])) {
        echo json_encode($commentSystem->getComments($podcast_id));
    } elseif (isset($_GET['get_rating'])) {
        echo json_encode($commentSystem->getAverageRating($podcast_id));
    }
}

$conn->close();
?>