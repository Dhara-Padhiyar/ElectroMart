<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "vendor") {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Detail | Vendor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/stylesheet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h2>Message Detail</h2>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </header>

        
        <main class="dashboard-content">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Question about my order</h5>
                    <div>
                        <span class="badge bg-warning">Pending</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-4">
                        <img src="https://via.placeholder.com/60" class="rounded-circle me-3" width="60" height="60">
                        <div>
                            <h6>John Smith</h6>
                            <p class="text-muted mb-0">john.smith@example.com</p>
                            <p class="text-muted">May 15, 2023 at 10:30 AM</p>
                        </div>
                    </div>
                    
                    <div class="mb-4 p-3 bg-light rounded">
                        <p>Hello,</p>
                        <p>I recently placed order #12345 and I was wondering when I can expect it to be shipped. The estimated delivery date was May 20, but I haven't received any shipping confirmation yet.</p>
                        <p>Could you please provide an update on my order status?</p>
                        <p>Thank you,<br>John Smith</p>
                    </div>

                    <div class="mb-3">
                        <h6>Attachments</h6>
                        <div class="d-flex gap-2">
                            <div class="border p-2 rounded">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                <a href="#">order_details.pdf</a>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Reply to Message</h5>
                    <form>
                        <div class="mb-3">
                            <textarea class="form-control" rows="5" placeholder="Type your reply here..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="replyAttachments" class="form-label">Attachments</label>
                            <input class="form-control" type="file" id="replyAttachments" multiple>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="markAsResolved">
                                <label class="form-check-label" for="markAsResolved">
                                    Mark as resolved
                                </label>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-save"></i> Save Draft
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Send Reply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Previous Messages</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-4">
                        <img src="https://via.placeholder.com/40" class="rounded-circle me-3" width="40" height="40">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6>Support Team</h6>
                                <small class="text-muted">May 10, 2023 at 2:15 PM</small>
                            </div>
                            <div class="p-3 bg-light rounded mt-2">
                                <p>Hello John,</p>
                                <p>Thank you for your order! Your items are being processed and will be shipped within 24 hours.</p>
                                <p>Best regards,<br>Support Team</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <img src="https://via.placeholder.com/40" class="rounded-circle me-3" width="40" height="40">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6>John Smith</h6>
                                <small class="text-muted">May 8, 2023 at 9:30 AM</small>
                            </div>
                            <div class="p-3 bg-light rounded mt-2">
                                <p>Hello,</p>
                                <p>I'm interested in your premium headphones. Do you offer international shipping?</p>
                                <p>Thanks,<br>John</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>