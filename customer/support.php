<?php 
// Include configuration
include '../config/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get user's recent tickets and orders from database
$user_id = $_SESSION['user_id'] ?? 0;
$tickets = [];
$recent_orders = [];

// Database query to get user's tickets (example - replace with your actual query)
/*
$stmt = $pdo->prepare("SELECT * FROM support_tickets WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get recent orders for dropdown
$stmt = $pdo->prepare("SELECT id, order_date, total FROM orders 
                      WHERE user_id = ? ORDER BY order_date DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
*/

// For demo purposes, we'll use sample data
$tickets = [
    [
        'id' => 'TKT1001',
        'subject' => 'Order not received',
        'department' => 'Shipping',
        'status' => 'open',
        'created_at' => '2023-05-20',
        'message' => 'My order #ORD2023-1056 was supposed to arrive on May 18 but I still haven\'t received it.'
    ],
    [
        'id' => 'TKT1002',
        'subject' => 'Wrong item in my order',
        'department' => 'Returns',
        'status' => 'pending',
        'created_at' => '2023-05-15',
        'message' => 'I received a black phone case instead of the blue one I ordered in order #ORD2023-1055'
    ]
];

$recent_orders = [
    ['id' => 'ORD2023-1056', 'order_date' => '2023-05-15', 'total' => 145.99],
    ['id' => 'ORD2023-1055', 'order_date' => '2023-05-10', 'total' => 89.50]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Support</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/stylesheet.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="dashboard-container">
        <?php include 'dashboard-sidebar.php'; ?>
        
        <main class="support-main-content">
            <h1><i class="fas fa-envelope"></i> Contact Support</h1>
            
            <div class="support-container">
                <div class="support-tabs">
                    <div class="support-tab active" data-tab="new">New Ticket</div>
                    <div class="support-tab" data-tab="tickets">My Tickets</div>
                </div>
                
                <div id="new-ticket-form">
                    <form class="support-form" method="post" action="submit-ticket.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select id="department" name="department" required>
                                <option value="">Select a department</option>
                                <option value="Billing">Billing & Payments</option>
                                <option value="Shipping">Shipping & Delivery</option>
                                <option value="Returns">Returns & Refunds</option>
                                <option value="Technical">Technical Support</option>
                                <option value="General">General Inquiry</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="order_id">Related Order (optional)</label>
                            <select id="order_id" name="order_id">
                                <option value="">Select an order</option>
                                <?php foreach ($recent_orders as $order): ?>
                                <option value="<?php echo $order['id']; ?>">
                                    Order #<?php echo $order['id']; ?> - <?php echo date('M d, Y', strtotime($order['order_date'])); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="attachments">Attachments (optional)</label>
                            <input type="file" id="attachments" name="attachments[]" multiple>
                            <small>You can upload up to 3 files (max 2MB each)</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Ticket
                        </button>
                    </form>
                </div>
                
                <div id="tickets-list" class="support-tickets" style="display: none;">
                    <?php if (!empty($tickets)): ?>
                        <?php foreach ($tickets as $ticket): ?>
                        <div class="ticket-item">
                            <div class="ticket-header">
                                <div>
                                    <span class="ticket-id">Ticket #<?php echo $ticket['id']; ?></span>
                                    <span class="ticket-date"><?php echo date('M d, Y', strtotime($ticket['created_at'])); ?></span>
                                </div>
                                <span class="ticket-status status-<?php echo strtolower($ticket['status']); ?>">
                                    <?php echo ucfirst($ticket['status']); ?>
                                </span>
                            </div>
                            
                            <div class="ticket-subject"><?php echo $ticket['subject']; ?></div>
                            <div class="ticket-message"><?php echo nl2br(htmlspecialchars(substr($ticket['message'], 0, 200) . '...')); ?></div>
                            
                            <div class="ticket-actions">
                                <a href="ticket-details.php?id=<?php echo $ticket['id']; ?>">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                <?php if ($ticket['status'] !== 'closed'): ?>
                                <a href="#">
                                    <i class="fas fa-reply"></i> Add Reply
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-tickets">
                            <i class="fas fa-envelope-open" style="font-size: 50px; margin-bottom: 15px;"></i>
                            <h3>No Support Tickets</h3>
                            <p>You haven't submitted any support tickets yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script>
        // Tab switching
        document.querySelectorAll('.support-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelector('.support-tab.active').classList.remove('active');
                this.classList.add('active');
                
                if (this.getAttribute('data-tab') === 'new') {
                    document.getElementById('new-ticket-form').style.display = 'block';
                    document.getElementById('tickets-list').style.display = 'none';
                } else {
                    document.getElementById('new-ticket-form').style.display = 'none';
                    document.getElementById('tickets-list').style.display = 'block';
                }
            });
        });
        
        // File upload validation
        document.getElementById('attachments').addEventListener('change', function() {
            const files = this.files;
            if (files.length > 3) {
                alert('You can upload a maximum of 3 files.');
                this.value = '';
                return;
            }
            
            for (let i = 0; i < files.length; i++) {
                if (files[i].size > 2 * 1024 * 1024) {
                    alert('File "' + files[i].name + '" exceeds the 2MB limit.');
                    this.value = '';
                    return;
                }
            }
        });
        
        // Auto-fill subject when order is selected
        document.getElementById('order_id').addEventListener('change', function() {
            const orderId = this.value;
            const subjectField = document.getElementById('subject');
            
            if (orderId && !subjectField.value) {
                subjectField.value = `Regarding Order #${orderId}`;
            }
        });
    </script>
</body>
</html>