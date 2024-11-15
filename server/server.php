<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}


/* $username = $_POST['username'];
$pwd = $_POST['password']; */
$errors = array();

//connect to the database
$db = mysqli_connect('localhost', 'root', 'Carlos.6693?', 'quizify_db');

/* //first check the database to make sure 
//a user does not already exist with same credentials
$user_check_query = "SELECT * FROM myguests WHERE username = '$username' OR pwd = '$pwd' LIMIT 1";
$result = mysqli_query($db, $user_check_query);
$user = mysqli_fetch_assoc($result);

if($user){ //if user  exists
   if($user['username']=== $username){
 array_push($errors, "Username already exists");
}

if($user['pwd']=== $pwd){
    array_push($errors,"email already exists");
}
} */

if(isset($_POST['login-submit'])){
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $pwd = mysqli_real_escape_string($db, $_POST['password']);

    if(empty($username)){
        array_push($errors, "Username is required");
    }
    if(empty($pwd)){
        array_push($errors, "Password is required");
    }

    if(count($errors) ==0){

        $pwd = $pwd; // should encrypt the password by md5($pwd)
        $query = "SELECT * FROM users WHERE username ='$username' AND pwd='$pwd'";
        $results = mysqli_query($db, $query);
        if(mysqli_num_rows($results) == 1){
            $_SESSION['username'] = $username;
            $_SESSION['success']= "You are now logged in";
            header('location: ../homePage.html');
        }
        else{
            array_push($errors, "Wrong username/password combination");
        }
    }
}



//
if(isset($_POST['createQuiz-submit'])){

    $servername = "localhost";
    $username = "root";
    $password = "Carlos.6693?";
    $dbName = "quizify_db";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbName);
    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";


    $quiz_title = $_POST['quiz-title'];
    $quiz_description = $_POST['quiz-description'];
    //gotta change the '1' for users own user_id, eventually but its 1 bcs im practicing with 
    //putting form in database
    $sql = "INSERT INTO quizzes (user_id, quiz_title, description) VALUES ('1', '$quiz_title', '$quiz_description')";
    mysqli_query($conn, $sql);
    $quiz_id = $conn->insert_id;


// Loop through each question in the form data
foreach ($_POST['questions'] as $question) {
    $question_text = $question['text'];
    $choices = $question['choices'];
    $correct_choice = $question['correct'];



    // Insert the question into the questions table using hardcoded values
    $question_text_escaped = $conn->real_escape_string($question_text);
    $insert_question_sql = "INSERT INTO questions (quiz_id, question_text) VALUES ('$quiz_id', '$question_text_escaped')";
    
    if ($conn->query($insert_question_sql) === TRUE) {
        $question_id = $conn->insert_id;

        // Insert each choice into the choices table using hardcoded values
        foreach ($choices as $index => $choice) {
            $choice_escaped = $conn->real_escape_string($choice);
            // Check if this option is the correct one
            $is_correct = ($index + 1 == $correct_choice) ? 1 : 0;
            $insert_choice_sql = "INSERT INTO choices (question_id, choice_text, is_correct) 
                                  VALUES ($question_id, '$choice_escaped', $is_correct)";
            $conn->query($insert_choice_sql);
        }
    }
} 

$result = mysqli_query($conn, $sql);
// Close the database connection
$conn->close();

header('location: ../homePage.html?');

echo "Quiz submitted successfully!"; 
}




?>