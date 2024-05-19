<?php
// Database connection code
$host = 'localhost';
$dbname = 'watosey';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

function register($username, $password, $faceDescriptor, $pdo) {
    try {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':username' => $username]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            echo "Username already exists. Please choose a different username.";
            return;
        }

        $query = "INSERT INTO users (username, password, face_descriptor) VALUES (:username, :password, :face_descriptor)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':username' => $username, ':password' => $password, ':face_descriptor' => $faceDescriptor]);

        echo "Registration successful. You can now <a href='index.php'>login</a>.";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $faceDescriptor = $_POST["faceDescriptor"];
    register($username, $password, $faceDescriptor, $pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
       <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Load face-api.js from local files -->
    <script defer src="face-api.js"></script>
    <!-- Load your script.js -->
    <script defer src="script.js"></script>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="hidden" name="faceDescriptor" id="faceDescriptor">
            <button type="submit">Register</button>
        </form>
        <!-- Ensure the captureButton is present -->
        <button id="captureButton">Capture Face</button>
        <a href="index.php" class="back-link">Back</a>
        <video id="video" width="720" height="560" style="display:none;"></video>
    </div>
</body>
</html>
