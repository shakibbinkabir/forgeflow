<?php ob_start(); ?>

<h1>Add New User</h1>

<form method="POST">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">Minimum 8 characters recommended</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Role *</label>
                        <select class="form-select" id="role" name="role" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role ?>"><?= $role ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">
                            <strong>Admin:</strong> Full access to all features<br>
                            <strong>Team Member:</strong> Can manage orders and communicate with customers
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3 text-center">
        <button type="submit" class="btn btn-primary">Create User</button>
        <a href="/users" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<?php
$content = ob_get_clean();
$title = 'Add User';
include '../layout.php';
?>