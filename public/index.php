<?php
session_start();

// Load configuration
$config = require __DIR__ . '/../config/app.php';

// Simple autoloader (since we don't have Composer yet)
spl_autoload_register(function ($className) {
    $className = str_replace('ForgeFlow\\', '', $className);
    $file = __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Initialize database
try {
    $db = \ForgeFlow\Database::getInstance();
    $pdo = $db->getConnection();
    
    // Run migrations if database is empty (supports MySQL and SQLite)
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    $hasTables = false;
    if ($driver === 'mysql') {
        $stmt = $pdo->query("SELECT COUNT(*) AS cnt FROM information_schema.tables WHERE table_schema = DATABASE()");
        $row = $stmt->fetch();
        $hasTables = isset($row['cnt']) ? (int)$row['cnt'] > 0 : false;
    } else { // sqlite and others
        $stmt = $pdo->query("SELECT COUNT(*) AS cnt FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $row = $stmt->fetch();
        $hasTables = isset($row['cnt']) ? (int)$row['cnt'] > 0 : false;
    }

    if (!$hasTables) {
        $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
        $pdo->exec($schema);
    }
} catch (Exception $e) {
    die("Database initialization failed: " . $e->getMessage());
}

// Simple router
class Router
{
    private $routes = [];

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Normalize for subdirectory deployments (e.g., /forgeflow/public)
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        $path = str_replace('\\', '/', $path);
        if ($basePath && $basePath !== '/' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
            if ($path === '' || $path === false) { $path = '/'; }
        }
        
        // Remove trailing slash except for root
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            if (is_callable($callback)) {
                $callback();
            } elseif (is_string($callback)) {
                require __DIR__ . '/../src/Controllers/' . $callback . '.php';
            }
        } else {
            http_response_code(404);
            require __DIR__ . '/../src/Views/404.php';
        }
    }
}

// Authentication helper
class Auth
{
    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    public static function user()
    {
        if (self::check()) {
            $userModel = new \ForgeFlow\Models\User();
            return $userModel->find($_SESSION['user_id']);
        }
        return null;
    }

    public static function login($user)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
    }

    public static function logout()
    {
        session_destroy();
    }

    public static function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Admin';
    }

    public static function requireAuth()
    {
        if (!self::check()) {
            redirect('/login');
        }
    }

    public static function requireAdmin()
    {
        self::requireAuth();
        if (!self::isAdmin()) {
            header('HTTP/1.1 403 Forbidden');
            die('Access denied');
        }
    }
}

// Utility functions
function redirect($path)
{
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    $target = ($basePath && $basePath !== '/') ? $basePath . $path : $path;
    header("Location: {$target}");
    exit;
}

function view($name, $data = [])
{
    extract($data);
    require __DIR__ . "/../src/Views/{$name}.php";
}

function asset($path)
{
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    $prefix = ($basePath && $basePath !== '/') ? $basePath . '/' : '/';
    return $prefix . ltrim($path, '/');
}

// Include layout from views root using absolute path
function include_layout()
{
    require __DIR__ . '/../src/Views/layout.php';
}

function uploadFile($file, $directory = 'general')
{
    $uploadDir = __DIR__ . "/../uploads/{$directory}/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return "{$directory}/{$fileName}";
    }
    
    return false;
}

// Create router instance
$router = new Router();

// Define routes
$router->get('/', function() {
    require __DIR__ . '/../src/Controllers/DashboardController.php';
});

$router->get('/login', function() {
    require __DIR__ . '/../src/Controllers/AuthController.php';
});

$router->post('/login', function() {
    require __DIR__ . '/../src/Controllers/AuthController.php';
});

$router->get('/logout', function() {
    Auth::logout();
    redirect('/login');
});

$router->get('/orders', function() {
    require __DIR__ . '/../src/Controllers/OrderController.php';
});

$router->post('/orders', function() {
    require __DIR__ . '/../src/Controllers/OrderController.php';
});

$router->get('/messages', function() {
    require __DIR__ . '/../src/Controllers/MessageController.php';
});

$router->post('/messages', function() {
    require __DIR__ . '/../src/Controllers/MessageController.php';
});

$router->get('/customers', function() {
    require __DIR__ . '/../src/Controllers/CustomerController.php';
});

$router->get('/users', function() {
    require __DIR__ . '/../src/Controllers/UserController.php';
});

$router->post('/users', function() {
    require __DIR__ . '/../src/Controllers/UserController.php';
});

$router->get('/reports', function() {
    require __DIR__ . '/../src/Controllers/ReportController.php';
});

$router->get('/uploads', function() {
    // Handle file serving
    $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    if ($basePath && $basePath !== '/' && strpos($requestPath, $basePath) === 0) {
        $requestPath = substr($requestPath, strlen($basePath));
    }
    $filePath = __DIR__ . '/..' . $requestPath;
    
    if (file_exists($filePath) && is_file($filePath)) {
        $mimeType = mime_content_type($filePath);
        header('Content-Type: ' . $mimeType);
        readfile($filePath);
    } else {
        http_response_code(404);
        echo 'File not found';
    }
});

// Run the router
$router->run();