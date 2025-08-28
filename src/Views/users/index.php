<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>User Management</h1>
    <a href="<?= asset('/users?action=create') ?>" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add User
    </a>
</div>

    include __DIR__ . '/../layout.php';
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="badge bg-<?= $user['role'] === 'Admin' ? 'danger' : 'secondary' ?>">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <a href="<?= asset('/users?action=edit&id=' . $user['id']) ?>" 
                                   class="btn btn-sm btn-outline-primary">Edit</a>
                                <?php if ($user['id'] != Auth::user()['id']): ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal<?= $user['id'] ?>">
                                        Delete
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modals -->
<?php foreach ($users as $user): ?>
    <?php if ($user['id'] != Auth::user()['id']): ?>
        <div class="modal fade" id="deleteModal<?= $user['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete user <strong><?= htmlspecialchars($user['name']) ?></strong>?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form method="POST" action="<?= asset('/users?action=delete&id=' . $user['id']) ?>" class="d-inline">
                            <button type="submit" class="btn btn-danger">Delete User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
$title = 'User Management';
include_layout();
?>