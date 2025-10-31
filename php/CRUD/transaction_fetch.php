<?php
<?php
require_once('../database.php');

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT 
        transaction_id,
        receipt_id,
        customer_name,
        amount,
        transaction_date,
        status
    FROM transactions 
    ORDER BY transaction_date DESC";

    $stmt = $db->prepare($query);
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($transactions);
    exit;

} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}