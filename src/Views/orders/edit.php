<?php ob_start(); ?>

<h1>Edit Order #<?= htmlspecialchars($order['order_number']) ?></h1>

<form method="POST">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($order['description']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="material" class="form-label">Material</label>
                                <select class="form-select" id="material" name="material">
                                    <option value="">Select Material</option>
                                    <option value="PLA" <?= $order['material'] === 'PLA' ? 'selected' : '' ?>>PLA</option>
                                    <option value="ABS" <?= $order['material'] === 'ABS' ? 'selected' : '' ?>>ABS</option>
                                    <option value="PETG" <?= $order['material'] === 'PETG' ? 'selected' : '' ?>>PETG</option>
                                    <option value="TPU" <?= $order['material'] === 'TPU' ? 'selected' : '' ?>>TPU</option>
                                    <option value="WOOD" <?= $order['material'] === 'WOOD' ? 'selected' : '' ?>>WOOD</option>
                                    <option value="Other" <?= $order['material'] === 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="color" name="color" 
                                       value="<?= htmlspecialchars($order['color']) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                       value="<?= $order['price'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tracking_number" class="form-label">Tracking Number</label>
                                <input type="text" class="form-control" id="tracking_number" name="tracking_number" 
                                       value="<?= htmlspecialchars($order['tracking_number']) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Internal Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?= htmlspecialchars($order['notes']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Status Management -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Status Management</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Current Status</label>
                        <select class="form-select" id="status" name="status">
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>>
                                    <?= $status ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status_notes" class="form-label">Status Change Notes</label>
                        <textarea class="form-control" id="status_notes" name="status_notes" rows="2" 
                                  placeholder="Optional notes about the status change..."></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h5>Customer Information</h5>
                </div>
                <div class="card-body">
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
        </div>
    </div>
    
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Update Order</button>
    <a href="<?= asset('/orders?action=view&id=' . $order['id']) ?>" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<?php
$content = ob_get_clean();
$title = 'Edit Order #' . $order['order_number'];
include __DIR__ . '/../layout.php';
?>