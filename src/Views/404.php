<?php ob_start(); ?>

<div class="text-center">
    <h1 class="display-1">404</h1>
    <h2>Page Not Found</h2>
    <p class="lead">The page you're looking for doesn't exist.</p>
    <a href="/" class="btn btn-primary">Return to Dashboard</a>
</div>

<?php
$content = ob_get_clean();
$title = '404 Not Found';
include 'layout.php';
?>