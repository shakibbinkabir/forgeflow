<?php
use ForgeFlow\Models\Order;
use ForgeFlow\Models\Customer;

Auth::requireAuth();

$orderModel = new Order();
$customerModel = new Customer();

$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $customerData = [
                'name' => $_POST['customer_name'],
                'email' => $_POST['customer_email'] ?? '',
                'phone' => $_POST['customer_phone'] ?? '',
                'address' => $_POST['customer_address'] ?? '',
            ];
            
            // Check if customer exists or create new one
            $customer = null;
            if (!empty($_POST['customer_phone'])) {
                $customer = $customerModel->findByPhone($_POST['customer_phone']);
            }
            if (!$customer && !empty($_POST['customer_email'])) {
                $customer = $customerModel->findByEmail($_POST['customer_email']);
            }
            
            if (!$customer) {
                $customerId = $customerModel->create($customerData);
            } else {
                $customerId = $customer['id'];
            }
            
            $orderData = [
                'customer_id' => $customerId,
                'order_number' => $orderModel->generateOrderNumber(),
                'material' => $_POST['material'] ?? '',
                'color' => $_POST['color'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'description' => $_POST['description'] ?? '',
            ];
            
            // Handle file uploads
            if (!empty($_FILES['design_file']['name'])) {
                $designFile = uploadFile($_FILES['design_file'], 'designs');
                if ($designFile) {
                    $orderData['design_file'] = $designFile;
                }
            }
            
            if (!empty($_FILES['reference_image']['name'])) {
                $referenceImage = uploadFile($_FILES['reference_image'], 'images');
                if ($referenceImage) {
                    $orderData['reference_image'] = $referenceImage;
                }
            }
            
            $orderId = $orderModel->create($orderData);
            redirect('/orders?action=view&id=' . $orderId);
        }
        
        view('orders/create');
        break;
        
    case 'view':
        $order = $orderModel->findWithCustomer($id);
        if (!$order) {
            http_response_code(404);
            view('404');
            return;
        }
        
        $statusHistory = $orderModel->getStatusHistory($id);
        view('orders/view', ['order' => $order, 'statusHistory' => $statusHistory]);
        break;
        
    case 'edit':
        $order = $orderModel->findWithCustomer($id);
        if (!$order) {
            http_response_code(404);
            view('404');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updateData = [
                'material' => $_POST['material'] ?? $order['material'],
                'color' => $_POST['color'] ?? $order['color'],
                'price' => $_POST['price'] ?? $order['price'],
                'description' => $_POST['description'] ?? $order['description'],
                'tracking_number' => $_POST['tracking_number'] ?? $order['tracking_number'],
                'notes' => $_POST['notes'] ?? $order['notes'],
            ];
            
            // Handle status change
            if (!empty($_POST['status']) && $_POST['status'] !== $order['status']) {
                $orderModel->updateStatus($id, $_POST['status'], Auth::user()['id'], $_POST['status_notes'] ?? null);
            }
            
            $orderModel->update($id, $updateData);
            redirect('/orders?action=view&id=' . $id);
        }
        
        view('orders/edit', ['order' => $order, 'statuses' => Order::getStatuses()]);
        break;
        
    case 'delete':
        Auth::requireAdmin();
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderModel->delete($id);
            redirect('/');
        }
        break;
        
    default:
        // List orders
        $statusFilter = $_GET['status'] ?? '';
        $searchFilter = $_GET['search'] ?? '';
        
        $filters = [];
        if ($statusFilter) $filters['status'] = $statusFilter;
        if ($searchFilter) $filters['search'] = $searchFilter;
        
        $orders = $orderModel->findAllWithCustomer($filters);
        view('orders/index', [
            'orders' => $orders,
            'statusFilter' => $statusFilter,
            'searchFilter' => $searchFilter,
            'orderStatuses' => Order::getStatuses(),
        ]);
}