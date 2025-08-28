<?php 
ob_start();
function getStatusBadgeClass($status) {
    return 'badge status-badge status-' . strtolower(str_replace([' ', '-'], '-', $status));
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Order Details</h1>
    <div>
        <a href="/orders?action=edit&id=<?= $order['id'] ?>" class="btn btn-primary">Edit Order</a>
        <a href="/orders" class="btn btn-secondary">Back to Orders</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Order Information -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Order #<?= htmlspecialchars($order['order_number']) ?></h5>
                <span class="<?= getStatusBadgeClass($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Order Details</h6>
                        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($order['description'])) ?></p>
                        <p><strong>Material:</strong> <?= htmlspecialchars($order['material'] ?: 'Not specified') ?></p>
                        <p><strong>Color:</strong> <?= htmlspecialchars($order['color'] ?: 'Not specified') ?></p>
                        <p><strong>Price:</strong> $<?= number_format($order['price'], 2) ?></p>
                        <p><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></p>
                        <p><strong>Last Updated:</strong> <?= date('M j, Y g:i A', strtotime($order['updated_at'])) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                        <?php if ($order['customer_email']): ?>
                            <p><strong>Email:</strong> 
                                <a href="mailto:<?= htmlspecialchars($order['customer_email']) ?>">
                                    <?= htmlspecialchars($order['customer_email']) ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php if ($order['customer_phone']): ?>
                            <p><strong>Phone:</strong> 
                                <a href="tel:<?= htmlspecialchars($order['customer_phone']) ?>">
                                    <?= htmlspecialchars($order['customer_phone']) ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php if ($order['customer_address']): ?>
                            <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order['customer_address'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Files Section -->
                <?php if ($order['design_file'] || $order['reference_image']): ?>
                    <hr>
                    <h6>Files</h6>
                    <div class="row">
                        <?php if ($order['design_file']): ?>
                            <div class="col-md-6">
                                <p><strong>Design File:</strong> 
                                    <a href="/uploads/<?= htmlspecialchars($order['design_file']) ?>" 
                                       class="btn btn-sm btn-outline-primary" download>
                                        <i class="bi bi-download"></i> Download Design
                                    </a>
                                </p>
                            </div>
                        <?php endif; ?>
                        <?php if ($order['reference_image']): ?>
                            <div class="col-md-6">
                                <p><strong>Reference Image:</strong></p>
                                <img src="/uploads/<?= htmlspecialchars($order['reference_image']) ?>" 
                                     class="img-thumbnail" style="max-width: 200px;" alt="Reference Image">
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Tracking Information -->
                <?php if ($order['tracking_number']): ?>
                    <hr>
                    <p><strong>Tracking Number:</strong> <?= htmlspecialchars($order['tracking_number']) ?></p>
                <?php endif; ?>
                
                <!-- Notes -->
                <?php if ($order['notes']): ?>
                    <hr>
                    <h6>Notes</h6>
                    <p><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Status History -->
        <div class="card">
            <div class="card-header">
                <h5>Status History</h5>
            </div>
            <div class="card-body">
                <?php if (empty($statusHistory)): ?>
                    <p class="text-muted">No status changes recorded.</p>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($statusHistory as $history): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <strong><?= htmlspecialchars($history['new_status']) ?></strong>
                                    <small class="text-muted">
                                        <?= date('M j, g:i A', strtotime($history['changed_at'])) ?>
                                    </small>
                                </div>
                                <?php if ($history['changed_by_name']): ?>
                                    <small class="text-muted">by <?= htmlspecialchars($history['changed_by_name']) ?></small>
                                <?php endif; ?>
                                <?php if ($history['notes']): ?>
                                    <p class="mb-0 mt-1"><small><?= nl2br(htmlspecialchars($history['notes'])) ?></small></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/messages?order_id=<?= $order['id'] ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-chat"></i> Send Message
                    </a>
                    <?php if (Auth::isAdmin()): ?>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i> Delete Order
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<?php if (Auth::isAdmin()): ?>
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete order #<?= htmlspecialchars($order['order_number']) ?>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="/orders?action=delete&id=<?= $order['id'] ?>" class="d-inline">
                    <button type="submit" class="btn btn-danger">Delete Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = 'Order #' . $order['order_number'];
include '../layout.php';
?>