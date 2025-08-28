<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Orders</h1>
    <a href="<?= asset('/orders?action=create') ?>" class="btn btn-primary">
        <i class="bi bi-plus"></i> New Order
    </a>
</div>

<form method="GET" class="row g-3 mb-3">
    <input type="hidden" name="action" value="index">
    <div class="col-md-4">
        <input type="text" name="search" value="<?= htmlspecialchars($searchFilter) ?>" class="form-control" placeholder="Search orders, customers, descriptions">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Statuses</option>
            <?php foreach ($orderStatuses as $st): ?>
                <option value="<?= htmlspecialchars($st) ?>" <?= $statusFilter === $st ? 'selected' : '' ?>><?= htmlspecialchars($st) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="6" class="text-center p-4 text-muted">No orders found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $o): ?>
                            <tr>
                                <td><?= (int)$o['id'] ?></td>
                                <td>
                                    <div><strong><?= htmlspecialchars($o['order_number']) ?></strong></div>
                                    <div class="text-muted small"><?= htmlspecialchars($o['material'] ?: '-') ?> <?= htmlspecialchars($o['color'] ?: '') ?></div>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($o['customer_name']) ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($o['customer_email'] ?: '-') ?> <?= htmlspecialchars($o['customer_phone'] ?: '') ?></div>
                                </td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($o['status']) ?></span></td>
                                <td><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
                                <td class="text-end">
                                    <a href="<?= asset('/orders?action=view&id=' . $o['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Orders';
include_layout();
