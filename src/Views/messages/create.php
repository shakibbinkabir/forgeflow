<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Add Message for Order #<?= htmlspecialchars($order['order_number']) ?></h1>
    <a href="<?= asset('/messages?order_id=' . $order['id']) ?>" class="btn btn-secondary">Back to Messages</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= asset('/messages?action=create&order_id=' . $order['id']) ?>">
            <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea class="form-control" name="message" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_from_customer" id="is_from_customer">
                    <label class="form-check-label" for="is_from_customer">This message is from the customer</label>
                </div>
            </div>
            <div class="mb-3" id="customer-name-field" style="display:none;">
                <label class="form-label">Customer Name</label>
                <input type="text" class="form-control" name="customer_name" value="<?= htmlspecialchars($order['customer_name']) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>

<script>
document.getElementById('is_from_customer').addEventListener('change', function() {
  document.getElementById('customer-name-field').style.display = this.checked ? 'block' : 'none';
});
</script>

<?php
$content = ob_get_clean();
$title = 'Add Message';
include __DIR__ . '/../layout.php';
