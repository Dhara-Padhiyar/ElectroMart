<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$result = $conn->query("SELECT refunds.id, refunds.order_id, users.name, refunds.reason, refunds.status, refunds.requested_at
                        FROM refunds JOIN users ON refunds.user_id = users.id");

include "header.php";
?>

<div class="container mt-4">
    <h2>Manage Refund Requests</h2>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["order_id"]; ?></td>
            <td><?php echo $row["name"]; ?></td>
            <td><?php echo $row["reason"]; ?></td>
            <td><?php echo ucfirst($row["status"]); ?></td>
            <td>
                <?php if ($row["status"] == "pending") { ?>
                    <a href="process_refund.php?id=<?php echo $row['id']; ?>&status=approved" class="btn btn-success btn-sm">Approve</a>
                    <a href="process_refund.php?id=<?php echo $row['id']; ?>&status=rejected" class="btn btn-danger btn-sm">Reject</a>
                <?php } else {
                    echo "Processed";
                } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<?php include "footer.php"; ?>
