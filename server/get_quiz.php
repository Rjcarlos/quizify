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

// Get the quiz ID from the query parameter
$quizId = isset($_GET['quizId']) ? intval($_GET['quizId']) : 0;

if ($quizId === 0) {
    echo json_encode(['error' => 'Invalid quiz ID']);
    exit();
}

// Fetch the quiz details
$quiz_sql = "SELECT quiz_title, description FROM quizzes WHERE id = ?";
$stmt = $conn->prepare($quiz_sql);
$stmt->bind_param('i', $quizId);
$stmt->execute();
$quiz_result = $stmt->get_result();

if ($quiz_result->num_rows === 0) {
    echo json_encode(['error' => 'Quiz not found']);
    exit();
}

$quiz = $quiz_result->fetch_assoc();

// Fetch the questions and choices
$questions_sql = "
    SELECT q.id AS question_id, q.question_text, c.choice_text, c.is_correct
    FROM questions q
    JOIN choices c ON q.id = c.question_id
    WHERE q.quiz_id = ?
";

$stmt = $conn->prepare($questions_sql);
$stmt->bind_param('i', $quizId);
$stmt->execute();
$questions_result = $stmt->get_result();

$questions = [];
$current_question_id = null;
$current_question = null;

while ($row = $questions_result->fetch_assoc()) {
    if ($row['question_id'] !== $current_question_id) {
        if ($current_question_id !== null) {
            $questions[] = $current_question;
        }

        $current_question_id = $row['question_id'];
        $current_question = [
            'text' => $row['question_text'],
            'choices' => []
        ];
    }

    $current_question['choices'][] = [
        'text' => $row['choice_text'],
        'is_correct' => $row['is_correct']
    ];
}

if ($current_question_id !== null) {
    $questions[] = $current_question;
}

// Return quiz data as JSON
echo json_encode([
    'quiz_title' => $quiz['quiz_title'],
    'description' => $quiz['description'],
    'questions' => $questions
]);

$conn->close();
?>
