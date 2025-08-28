<?php ob_start(); ?>

<h1>Reports & Analytics</h1>

<div class="row mb-4">
    <!-- Quick Stats -->
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <h2 class="text-primary"><?= array_sum($statusCounts) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Completed Orders</h5>
                <h2 class="text-success"><?= $statusCounts['Completed'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <h2 class="text-success">$<?= number_format($totalRevenue, 2) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">This Month</h5>
                <h2 class="text-info"><?= $monthlyData[date('n')] ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Export Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Data Exports</h5>
    </div>
    <div class="card-body">
        <p>Export data for business analysis or record-keeping purposes.</p>
        <div class="d-flex gap-2">
            <a href="/reports?action=export_orders" class="btn btn-primary">
                <i class="bi bi-download"></i> Export Orders (CSV)
            </a>
            <?php if (Auth::isAdmin()): ?>
                <a href="/reports?action=export_customers" class="btn btn-secondary">
                    <i class="bi bi-download"></i> Export Customers (CSV)
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Status Distribution Chart -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Order Status Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Monthly Order Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="trendsChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Status Distribution Pie Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_keys($statusCounts)) ?>,
        datasets: [{
            data: <?= json_encode(array_values($statusCounts)) ?>,
            backgroundColor: [
                '#ffc107', // Pending
                '#17a2b8', // Processing
                '#fd7e14', // Printing
                '#6f42c1', // Post-Processing
                '#28a745', // Ready for Steadfast
                '#007bff', // Shipped
                '#6c757d'  // Completed
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Monthly Trends Line Chart
const trendsCtx = document.getElementById('trendsChart').getContext('2d');
const trendsChart = new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Orders',
            data: <?= json_encode(array_values($monthlyData)) ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
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
$title = 'Reports';
include 'layout.php';
?>