<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ForgeFlow' ?> - 3D Printing Operations CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .status-pending { background-color: #ffc107; }
        .status-processing { background-color: #17a2b8; }
        .status-printing { background-color: #fd7e14; }
        .status-post-processing { background-color: #6f42c1; }
        .status-ready-for-steadfast { background-color: #28a745; }
        .status-shipped { background-color: #007bff; }
        .status-completed { background-color: #6c757d; }
        
        .sidebar {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        .main-content {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <?php if (Auth::check()): ?>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 col-lg-2 sidebar">
                    <div class="d-flex flex-column p-3">
                        <h4 class="mb-4">ForgeFlow</h4>
                        <nav class="nav nav-pills flex-column mb-auto">
                            <a href="/" class="nav-link <?= $_SERVER['REQUEST_URI'] == '/' ? 'active' : '' ?>">
                                <i class="bi bi-house-door me-2"></i>Dashboard
                            </a>
                            <a href="/orders" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/orders') === 0 ? 'active' : '' ?>">
                                <i class="bi bi-box me-2"></i>Orders
                            </a>
                            <a href="/messages" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/messages') === 0 ? 'active' : '' ?>">
                                <i class="bi bi-chat me-2"></i>Messages
                            </a>
                            <a href="/customers" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/customers') === 0 ? 'active' : '' ?>">
                                <i class="bi bi-people me-2"></i>Customers
                            </a>
                            <a href="/reports" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/reports') === 0 ? 'active' : '' ?>">
                                <i class="bi bi-graph-up me-2"></i>Reports
                            </a>
                            <?php if (Auth::isAdmin()): ?>
                                <a href="/users" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/users') === 0 ? 'active' : '' ?>">
                                    <i class="bi bi-person-gear me-2"></i>Users
                                </a>
                            <?php endif; ?>
                        </nav>
                        <hr>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
                               id="dropdownUser" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>
                                <?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>
                            <ul class="dropdown-menu text-small shadow">
                                <li><a class="dropdown-item" href="/logout">Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="col-md-9 col-lg-10 main-content">
                    <?= $content ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="container-fluid h-100">
            <?= $content ?>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>