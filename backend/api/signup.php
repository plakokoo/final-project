<?php
session_start();
header('Content-Type: application/json');
require_once '../DB.php';


date_default_timezone_set('Asia/Manila');

function generateID() {
    return random_int(10000000, 99999999);
}


$response = [
    'success' => false,
    'message' => 'Signup failed'
];


$input = json_decode(file_get_contents("php://input"), true);

$fullname = trim($input['fullname']?? '');
$email = strtolower(trim($input['username'] ?? ''));
$password = $input['password'] ?? '';
$confirmpassword = $input['confirmpassword'] ?? '';

if ( !$fullname || !$email || !$password || !$confirmpassword) {
    $response['message'] = 'All fields are required';
    echo json_encode($response);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Invalid email address';
    echo json_encode($response);
    exit();
}

if($password !== $confirmpassword) {
    $response['message'] = 'Password do not match';
    echo json_encode($response);
    exit();
}

$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);

if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    $response['message'] = 'Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.';
    echo json_encode($response);
    exit();
    
}

try {

    $db = new DB();
    $conn = $db->createConnection();

    $conn->query("SET time_zone = '+00:00'");



    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $response['message'] = 'Email already exists';
        echo json_encode($response);
        exit();
    }

    $stmt->close();

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $card_id = generateID();

    $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash, card_id, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $fullname, $email, $password_hash, $card_id);

    if($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['email'] = $email;
        $_SESSION['user_name'] = $fullname;
        $_SESSION['card_id'] = $card_id;
        session_regenerate_id(true);

        $response = ['success' => true];
    } else {
        $response['message'] = 'Database error';
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    $response['message'] = 'Server error';
}

echo json_encode($response);


?>