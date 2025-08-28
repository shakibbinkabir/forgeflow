<?php ob_start(); ?>

<h1>Customer Messages</h1>

<div class="mb-3">
    <a href="<?= asset('/orders') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Orders
    </a>
</div>

<?php if (empty($messages)): ?>
    <div class="alert alert-info">
        <h5>No Messages Yet</h5>
        <p>No customer communications have been recorded yet.</p>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>From</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                            <tr>
                                <td>
                                    <a href="<?= asset('/orders?action=view&id=' . $message['order_id']) ?>">
                                        <?= htmlspecialchars($message['order_number']) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($message['is_from_customer']): ?>
                                        <span class="badge bg-primary">Customer</span><br>
                                        <?= htmlspecialchars($message['customer_name']) ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Team</span><br>
                                        <?= htmlspecialchars($message['user_name'] ?: 'System') ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?= htmlspecialchars($message['message']) ?>
                                    </div>
                                </td>
                                <td><?= date('M j, Y g:i A', strtotime($message['created_at'])) ?></td>
                                <td>
                                    <a href="<?= asset('/messages?order_id=' . $message['order_id']) ?>" 
                                       class="btn btn-sm btn-outline-primary">View Thread</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = 'Messages';
include __DIR__ . '/../layout.php';
?>