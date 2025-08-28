<?php
function getStatusBadgeClass($status) {
    return 'badge status-badge status-' . strtolower(str_replace([' ', '-'], '-', $status));
}

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dashboard</h1>
    <a href="/orders?action=create" class="btn btn-primary">
        <i class="bi bi-plus"></i> New Order
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <h2 class="text-primary"><?= $totalOrders ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Pending</h5>
                <h2 class="text-warning"><?= $statusCounts['Pending'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">In Production</h5>
                <h2 class="text-info"><?= $statusCounts['Processing'] + $statusCounts['Printing'] + $statusCounts['Post-Processing'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Completed</h5>
                <h2 class="text-success"><?= $statusCounts['Completed'] ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Chart -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Monthly Order Volume</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" width="400" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <?php foreach ($orderStatuses as $status): ?>
                        <option value="<?= $status ?>" <?= $statusFilter === $status ? 'selected' : '' ?>>
                            <?= $status ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search orders..." 
                       value="<?= htmlspecialchars($searchFilter) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header">
        <h5>Recent Orders</h5>
    </div>
    <div class="card-body">
        <?php if (empty($orders)): ?>
            <div class="text-center py-4">
                <p class="text-muted">No orders found.</p>
                <a href="/orders?action=create" class="btn btn-primary">Create First Order</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Material</th>
                            <th>Price</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($order['order_number']) ?></strong></td>
                                <td>
                                    <?= htmlspecialchars($order['customer_name']) ?><br>
                                    <small class="text-muted"><?= htmlspecialchars($order['customer_phone']) ?></small>
                                </td>
                                <td>
                                    <span class="<?= getStatusBadgeClass($order['status']) ?>">
                                        <?= htmlspecialchars($order['status']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($order['material']) ?></td>
                                <td>$<?= number_format($order['price'], 2) ?></td>
                                <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <a href="/orders?action=view&id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Monthly chart
const ctx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Orders',
            data: <?= json_encode(array_values($monthlyData)) ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>