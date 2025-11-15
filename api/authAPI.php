<?php
require_once "../api/database.php";
require_once "../controllers/AuthController.php";

session_start();

$action = $_GET['action'] ?? '';
$auth = new AuthController($conn);

header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $auth = new AuthController($conn);

    if ($action === "login") {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $result = $auth->login($email, $password);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => $result['status'] ? 'success' : 'error',
            'message' => $result['message']
        ]);
        exit;
    }
    elseif ($action === "signup") {
            $full_name = $_POST['full_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $result = $auth->signup($full_name, $email, $password);

            echo json_encode($result);
            exit;

        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            exit;
        }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}
