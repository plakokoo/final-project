<?php
session_set_cookie_params(0, '/');
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => true,
        'already_logged_in' => true
    ]);
    exit();
}
require_once '../DB.php';


date_default_timezone_set('UTC');

$response = [
    'success' => false,
    'message' => 'Invalid email or password'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($response);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);
$email = strtolower(trim($input['username'] ?? ''));
$password = $input['password'] ?? '';

if ($email === '' || $password === '') {
    echo json_encode($response);
    exit();
}


$max_login_attempts = 5;
$lockout_time_seconds = 60; 

try {

    $db = new DB();
    $conn = $db->createConnection();


    $conn->query("SET time_zone = '+00:00'");

    $stmt = $conn->prepare("
        SELECT id, name, email, password_hash, card_id,
               failed_login_attempts, last_failed_login
        FROM users
        WHERE email = ?
        LIMIT 1
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {

        $user = $result->fetch_assoc();
        $remaining = 0;



        if ($user['failed_login_attempts'] >= $max_login_attempts
            && $user['last_failed_login'] !== null) {

            $lockStmt = $conn->prepare("
                SELECT TIMESTAMPDIFF(SECOND, last_failed_login, NOW()) AS seconds_passed
                FROM users
                WHERE id = ?
            ");
            $lockStmt->bind_param("i", $user['id']);
            $lockStmt->execute();
            $lockResult = $lockStmt->get_result()->fetch_assoc();
            $lockStmt->close();

            $elapsed = (int)$lockResult['seconds_passed'];

            if ($elapsed < $lockout_time_seconds) {
                $remaining = $lockout_time_seconds - $elapsed;

                echo json_encode([
                    'success' => false,
                    'lockout' => true,
                    'remaining_seconds' => $remaining
                ]);
                exit();
            } else {

                $resetStmt = $conn->prepare("
                    UPDATE users
                    SET failed_login_attempts = 0,
                        last_failed_login = NULL
                    WHERE id = ?
                ");
                $resetStmt->bind_param("i", $user['id']);
                $resetStmt->execute();
                $resetStmt->close();

                $user['failed_login_attempts'] = 0;
            }
        }



        if (password_verify($password, $user['password_hash'])) {


            $updateStmt = $conn->prepare("
                UPDATE users
                SET failed_login_attempts = 0,
                    last_failed_login = NULL,
                    last_login = NOW()
                WHERE id = ?
            ");
            $updateStmt->bind_param("i", $user['id']);
            $updateStmt->execute();
            $updateStmt->close();

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['card_id'] = $user['card_id'];

            echo json_encode(['success' => true]);
            exit();

        } else {


            $newAttempts = $user['failed_login_attempts'] + 1;

            $failStmt = $conn->prepare("
                UPDATE users
                SET failed_login_attempts = ?,
                    last_failed_login = NOW()
                WHERE id = ?
            ");
            $failStmt->bind_param("ii", $newAttempts, $user['id']);
            $failStmt->execute();
            $failStmt->close();

            if ($newAttempts >= $max_login_attempts) {
                echo json_encode([
                    'success' => false,
                    'lockout' => true,
                    'remaining_seconds' => $lockout_time_seconds
                ]);
                exit();
            }
        }
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Server error'
    ]);
    exit();
}

echo json_encode($response);
exit();