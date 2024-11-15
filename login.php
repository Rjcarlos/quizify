


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Website</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Welcome to Quizify</h1>
        <nav>

        </nav>
    </header>
    <main>
     <div class = "container">
        <form id = "signup-form" method="POST" action="server/server.php">
            <h1>Log In</h1>
            <label for = "username">Username</label>
            <input type = "text" id = "username" name = "username"  required/>

            <label for = "password">Password</label>
            <input type = "text" id = "password" name="password"  required/>

            <button type = "submit" id = "submit" value = "submit" name = "login-submit">Log in</button>
            <p><a href="signup.html">Create Acount</a></p>
        </form>
    </div>
    </main>
    <script src="js/script.js"></script>
</body>
</html>