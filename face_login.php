<?php
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

$data = json_decode(file_get_contents('php://input'), true);
$descriptor = $data['descriptor'];

$query = "SELECT username, face_descriptor FROM users";
$stmt = $pdo->query($query);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

function euclideanDistance($desc1, $desc2) {
    $sum = 0;
    for ($i = 0; $i < count($desc1); $i++) {
        $sum += pow($desc1[$i] - $desc2[$i], 2);
    }
    return sqrt($sum);
}

$threshold = 0.6; // Threshold for face match
$match = false;

foreach ($users as $user) {
    $dbDescriptor = json_decode($user['face_descriptor']);
    $distance = euclideanDistance($descriptor, $dbDescriptor);

    if ($distance < $threshold) {
        $match = true;
        break;
    }
}

echo json_encode(['success' => $match]);
?>
