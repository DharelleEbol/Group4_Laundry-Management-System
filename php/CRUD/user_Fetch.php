<?php
<?php
require_once('../database.php');

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM transactions ORDER BY transaction_date DESC";
$stmt = $db->prepare($query);
$stmt->execute();

$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($transactions);
?>