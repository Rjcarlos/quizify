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

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$quizId = isset($_GET['quizId']) ? intval($_GET['quizId']) : 0;
if ($quizId === 0) {
    echo json_encode(['error' => 'Invalid quiz ID']);
    exit();
}

// Get the user's answers from the form
$score = 0;
$totalQuestions = 0;

foreach ($_POST as $questionKey => $userAnswer) {
    // Extract the question index from the form data
    if (preg_match('/^question-(\d+)$/', $questionKey, $matches)) {
        $questionIndex = intval($matches[1]);

        // Fetch the correct answer for the question
        $correct_sql = "
            SELECT c.is_correct
            FROM questions q
            JOIN choices c ON q.id = c.question_id
            WHERE q.quiz_id = ? AND c.is_correct = 1
            LIMIT 1 OFFSET ?
        ";

        $stmt = $conn->prepare($correct_sql);
        $stmt->bind_param('ii', $quizId, $userAnswer);
        $stmt->execute();
        $correct_result = $stmt->get_result();

        if ($correct_result->num_rows > 0) {
            $correct_choice = $correct_result->fetch_assoc();
            if ($correct_choice['is_correct']) {
                $score++;  // Increment score if the user's answer is correct
            }
        }

        $totalQuestions++;
    }
}

// Return the score
echo json_encode([
    'score' => $score,
    'total' => $totalQuestions
]);

$conn->close();
?>
