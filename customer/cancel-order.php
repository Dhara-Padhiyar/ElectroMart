<?php
session_start();
include '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $order_id = isset($data['id']) ? (int)$data['id'] : 0;
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

    if (!$order_id || !$user_id) {
        echo json_encode(['success' => false, 'error' => 'Invalid request']);
        exit;
    }

    // Check if the order belongs to the user and is pending
    $stmt = $conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ? AND status = 'pending'");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update status to 'cancelled'
        $update = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
        $update->bind_param("i", $order_id);
        $success = $update->execute();

        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Order not found or cannot be cancelled']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
}
?>
