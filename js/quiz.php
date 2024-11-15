<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Database connection
$host = 'localhost';  // your host
$user = 'root';   // your DB username
$password = 'Carlos.6693?';  // your DB password
$dbname = 'quizify_db';  // your DB name

$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch quizzes (assuming user_id = 1, update later with real user authentication)
$user_id = 1; // replace with real user session ID in production
$sql = "SELECT id, quiz_title, description FROM quizzes WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();

$result = $stmt->get_result();
$quizzes = [];

while ($row = $result->fetch_assoc()) {
    $quizzes[] = $row;  // Collect each quiz into the array
}

header('Content-Type: application/json');  // Set content type to JSON
echo json_encode($quizzes);  // Return the quizzes as JSON

// Close the connection
$conn->close();
?>
