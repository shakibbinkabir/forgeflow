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
    
    // Run migrations if database is empty
    $tables = $db->getConnection()->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll();
    if (empty($tables)) {
        $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
        $db->getConnection()->exec($schema);
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
            header('Location: /login');
            exit;
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
    header("Location: {$path}");
    exit;
}

function view($name, $data = [])
{
    extract($data);
    require __DIR__ . "/../src/Views/{$name}.php";
}

function asset($path)
{
    return "/{$path}";
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

// Run the router
$router->run();