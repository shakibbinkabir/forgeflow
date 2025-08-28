<?php
use ForgeFlow\Models\Message;
use ForgeFlow\Models\Order;

Auth::requireAuth();

$messageModel = new Message();
$orderModel = new Order();

$action = $_GET['action'] ?? 'index';
$orderId = $_GET['order_id'] ?? null;

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $orderId) {
            $message = $_POST['message'] ?? '';
            $isFromCustomer = isset($_POST['is_from_customer']) ? 1 : 0;
            $customerName = $_POST['customer_name'] ?? null;
            
            if (!empty($message)) {
                $messageModel->createMessage(
                    $orderId,
                    $message,
                    $isFromCustomer ? null : Auth::user()['id'],
                    $customerName,
                    $isFromCustomer
                );
                redirect("/messages?order_id={$orderId}");
            }
        }
        
        $order = $orderModel->findWithCustomer($orderId);
        if (!$order) {
            http_response_code(404);
            view('404');
            return;
        }
        
        view('messages/create', ['order' => $order]);
        break;
        
    default:
        if ($orderId) {
            // Show messages for specific order
            $order = $orderModel->findWithCustomer($orderId);
            if (!$order) {
                http_response_code(404);
                view('404');
                return;
            }
            
            $messages = $messageModel->findByOrder($orderId);
            view('messages/order', [
                'order' => $order,
                'messages' => $messages,
            ]);
        } else {
            // Show all recent messages
            $recentMessages = $messageModel->getRecentMessages(50);
            view('messages/index', [
                'messages' => $recentMessages,
            ]);
        }
}