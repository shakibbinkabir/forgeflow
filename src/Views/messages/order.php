<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Messages for Order #<?= htmlspecialchars($order['order_number']) ?></h1>
    <div>
        <a href="/messages?action=create&order_id=<?= $order['id'] ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add Message
        </a>
        <a href="/orders?action=view&id=<?= $order['id'] ?>" class="btn btn-secondary">
            View Order
        </a>
    </div>
</div>

<!-- Order Summary -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h6>Order Details</h6>
                <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($order['description']) ?></p>
            </div>
            <div class="col-md-4">
                <h6>Status</h6>
                <span class="badge bg-info"><?= htmlspecialchars($order['status']) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Messages Thread -->
<div class="card">
    <div class="card-header">
        <h5>Message History</h5>
    </div>
    <div class="card-body">
        <?php if (empty($messages)): ?>
            <div class="text-center py-4">
                <p class="text-muted">No messages yet.</p>
                <a href="/messages?action=create&order_id=<?= $order['id'] ?>" class="btn btn-primary">
                    Start Conversation
                </a>
            </div>
        <?php else: ?>
            <div class="message-thread">
                <?php foreach ($messages as $message): ?>
                    <div class="message mb-4 p-3 <?= $message['is_from_customer'] ? 'bg-light border-start border-primary border-4' : 'bg-primary bg-opacity-10 border-start border-secondary border-4' ?>">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <?php if ($message['is_from_customer']): ?>
                                    <span class="badge bg-primary">Customer</span>
                                    <strong><?= htmlspecialchars($message['customer_name']) ?></strong>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Team Member</span>
                                    <strong><?= htmlspecialchars($message['user_name'] ?: 'System') ?></strong>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted">
                                <?= date('M j, Y g:i A', strtotime($message['created_at'])) ?>
                            </small>
                        </div>
                        <div>
                            <?= nl2br(htmlspecialchars($message['message'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <hr>
            
            <!-- Quick Reply Form -->
            <div class="mt-4">
                <h6>Quick Reply</h6>
                <form method="POST" action="/messages?action=create&order_id=<?= $order['id'] ?>">
                    <div class="mb-3">
                        <textarea class="form-control" name="message" rows="3" 
                                  placeholder="Type your message..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_from_customer" 
                                   id="is_from_customer">
                            <label class="form-check-label" for="is_from_customer">
                                This message is from the customer
                            </label>
                        </div>
                        <div id="customer-name-field" style="display: none;" class="mt-2">
                            <input type="text" class="form-control" name="customer_name" 
                                   placeholder="Customer name" value="<?= htmlspecialchars($order['customer_name']) ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('is_from_customer').addEventListener('change', function() {
    const customerNameField = document.getElementById('customer-name-field');
    customerNameField.style.display = this.checked ? 'block' : 'none';
});
</script>

<?php
$content = ob_get_clean();
$title = 'Messages - Order #' . $order['order_number'];
include '../layout.php';
?>