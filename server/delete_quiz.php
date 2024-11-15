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

// Get the quiz ID from the request
$quizId = isset($_GET['quizId']) ? intval($_GET['quizId']) : 0;

if ($quizId === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid quiz ID']);
    exit();
}

// Start a transaction to ensure data integrity
$conn->begin_transaction();

try {
    // Delete the associated choices first
    $deleteChoicesSql = "DELETE FROM choices WHERE question_id IN (SELECT id FROM questions WHERE quiz_id = ?)";
    $stmt = $conn->prepare($deleteChoicesSql);
    $stmt->bind_param('i', $quizId);
    $stmt->execute();

    // Delete the associated questions
    $deleteQuestionsSql = "DELETE FROM questions WHERE quiz_id = ?";
    $stmt = $conn->prepare($deleteQuestionsSql);
    $stmt->bind_param('i', $quizId);
    $stmt->execute();

    // Delete the quiz itself
    $deleteQuizSql = "DELETE FROM quizzes WHERE id = ?";
    $stmt = $conn->prepare($deleteQuizSql);
    $stmt->bind_param('i', $quizId);
    $stmt->execute();

    // Commit the transaction if everything is successful
    $conn->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Roll back the transaction if there is any error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
