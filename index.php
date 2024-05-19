<?php
// Database connection code
$host = 'localhost';
$dbname = 'watosey'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

function login($username, $password, $pdo) {
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username, 'password' => $password]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        header("Location: main.php");
        exit;
    } else {
        header("Location: index.php?error=1");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    login($username, $password, $pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <script defer src="\gabfinal\face-api.js"></script>
    <script defer src="script.js"></script>
</head>
<body>
    <div class="login-container">
    <h2>Login</h2>
    <form action="index.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a href="register.php" class="register-link">Register</a>
    <button id="startButton">Login with Face</button>
    <button id="captureButton">Capture</button>
    <video id="video" width="720" height="560" style="display:none;"></video>
</div>
</body>
</html>
