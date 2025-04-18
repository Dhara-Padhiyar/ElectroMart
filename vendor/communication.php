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
    <title>Communication | Vendor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/stylesheet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h2>Communication Center</h2>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </header>

      

        <main class="dashboard-content">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Notifications</h5>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">New Order #12345</div>
                                        Just now
                                    </div>
                                    <span class="badge bg-primary rounded-pill">1</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">Product Review</div>
                                        2 hours ago
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">Payment Received</div>
                                        Yesterday
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">Inventory Alert</div>
                                        May 12
                                    </div>
                                    <span class="badge bg-danger rounded-pill">3</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-envelope-plus"></i> New Message
                                </button>
                                <button class="btn btn-outline-success">
                                    <i class="bi bi-megaphone"></i> Send Announcement
                                </button>
                                <button class="btn btn-outline-secondary">
                                    <i class="bi bi-gear"></i> Notification Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Customer Messages</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Subject</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="cursor: pointer;" onclick="window.location='message_detail.php?id=1'">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2" width="30" height="30">
                                                    <div>John Smith</div>
                                                </div>
                                            </td>
                                            <td>Question about my order</td>
                                            <td>Today, 10:30 AM</td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                        </tr>
                                        <tr style="cursor: pointer;" onclick="window.location='message_detail.php?id=2'">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2" width="30" height="30">
                                                    <div>Sarah Johnson</div>
                                                </div>
                                            </td>
                                            <td>Product return request</td>
                                            <td>Yesterday, 4:15 PM</td>
                                            <td><span class="badge bg-info">In Progress</span></td>
                                        </tr>
                                        <tr style="cursor: pointer;" onclick="window.location='message_detail.php?id=3'">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2" width="30" height="30">
                                                    <div>Michael Brown</div>
                                                </div>
                                            </td>
                                            <td>Custom order inquiry</td>
                                            <td>May 10, 9:45 AM</td>
                                            <td><span class="badge bg-success">Resolved</span></td>
                                        </tr>
                                        <tr style="cursor: pointer;" onclick="window.location='message_detail.php?id=4'">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2" width="30" height="30">
                                                    <div>Emily Davis</div>
                                                </div>
                                            </td>
                                            <td>Shipping delay question</td>
                                            <td>May 8, 2:30 PM</td>
                                            <td><span class="badge bg-success">Resolved</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <nav aria-label="Message pagination">
                                <ul class="pagination justify-content-center mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5>Send Message to Customer</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label for="customerSelect" class="form-label">Select Customer</label>
                                    <select class="form-select" id="customerSelect">
                                        <option selected>Choose customer...</option>
                                        <option>John Smith (john@example.com)</option>
                                        <option>Sarah Johnson (sarah@example.com)</option>
                                        <option>Michael Brown (michael@example.com)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="messageSubject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="messageSubject">
                                </div>
                                <div class="mb-3">
                                    <label for="messageContent" class="form-label">Message</label>
                                    <textarea class="form-control" id="messageContent" rows="5"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="messageAttachments" class="form-label">Attachments</label>
                                    <input class="form-control" type="file" id="messageAttachments" multiple>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>