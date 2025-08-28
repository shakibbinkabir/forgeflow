<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Customers</h1>
    <a href="<?= asset('/orders?action=create') ?>" class="btn btn-primary">
        <i class="bi bi-plus"></i> New Order
    </a>
    </div>

<form method="get" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone" value="<?= htmlspecialchars($search ?? '') ?>">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
    <input type="hidden" name="action" value="index">
</form>

<?php if (empty($customers)): ?>
    <div class="alert alert-info">No customers found.</div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c['name']) ?></td>
                                <td><?= htmlspecialchars($c['email']) ?></td>
                                <td><?= htmlspecialchars($c['phone']) ?></td>
                                <td><?= htmlspecialchars($c['address']) ?></td>
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
$title = 'Customers';
include __DIR__ . '/../layout.php';
?>
