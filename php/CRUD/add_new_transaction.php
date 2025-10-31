<?php
<?php
require_once('../database.php');

// Ensure only JSON is output
header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT 
        transaction_id,
        receipt_id,
        customer_name,
        amount,
        transaction_date,
        status,
        weight,
        branch_id
    FROM transactions 
    ORDER BY transaction_date DESC";

    $stmt = $db->prepare($query);
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($transactions);

} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}