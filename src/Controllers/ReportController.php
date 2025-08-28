<?php
use ForgeFlow\Models\Order;
use ForgeFlow\Models\Customer;
use ForgeFlow\Database;

Auth::requireAuth();

$orderModel = new Order();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'export_orders':
        $orders = $orderModel->findAllWithCustomer();
        
        $filename = 'orders_export_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            'Order Number',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Status',
            'Material',
            'Color',
            'Price',
            'Description',
            'Created Date',
            'Updated Date',
            'Tracking Number'
        ]);
        
        // CSV data
        foreach ($orders as $order) {
            fputcsv($output, [
                $order['order_number'],
                $order['customer_name'],
                $order['customer_email'],
                $order['customer_phone'],
                $order['status'],
                $order['material'],
                $order['color'],
                $order['price'],
                $order['description'],
                $order['created_at'],
                $order['updated_at'],
                $order['tracking_number']
            ]);
        }
        
        fclose($output);
        exit;
        
    case 'export_customers':
        Auth::requireAdmin(); // Only admins can export customer data
        
        $customerModel = new Customer();
        $customers = $customerModel->findAll();
        
        $filename = 'customers_export_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Address',
            'Created Date',
            'Total Orders'
        ]);
        
        // CSV data
        foreach ($customers as $customer) {
            $orderCount = $orderModel->count(['customer_id' => $customer['id']]);
            
            fputcsv($output, [
                $customer['id'],
                $customer['name'],
                $customer['email'],
                $customer['phone'],
                $customer['address'],
                $customer['created_at'],
                $orderCount
            ]);
        }
        
        fclose($output);
        exit;
        
    default:
        // Show reports dashboard
        $statusCounts = $orderModel->getStatusCounts();
        $monthlyData = $orderModel->getMonthlyOrderCounts();
        
        $totalRevenue = Database::getInstance()->getConnection()
            ->query("SELECT COALESCE(SUM(price), 0) FROM orders WHERE status = 'Completed'")
            ->fetchColumn();
            
        view('reports/index', [
            'statusCounts' => $statusCounts,
            'monthlyData' => $monthlyData,
            'totalRevenue' => $totalRevenue,
        ]);
}