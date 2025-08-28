<?php
use ForgeFlow\Models\Order;
use ForgeFlow\Models\Customer;

Auth::requireAuth();

$orderModel = new Order();
$customerModel = new Customer();

// Get filter parameters
$statusFilter = $_GET['status'] ?? '';
$searchFilter = $_GET['search'] ?? '';

// Get dashboard statistics
$statusCounts = $orderModel->getStatusCounts();
$totalOrders = array_sum($statusCounts);
$monthlyData = $orderModel->getMonthlyOrderCounts();

// Get recent orders
$filters = [];
if ($statusFilter) $filters['status'] = $statusFilter;
if ($searchFilter) $filters['search'] = $searchFilter;

$orders = $orderModel->findAllWithCustomer($filters);

view('dashboard', [
    'orders' => $orders,
    'statusCounts' => $statusCounts,
    'totalOrders' => $totalOrders,
    'monthlyData' => $monthlyData,
    'statusFilter' => $statusFilter,
    'searchFilter' => $searchFilter,
    'orderStatuses' => Order::getStatuses(),
]);