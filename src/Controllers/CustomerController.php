<?php
use ForgeFlow\Models\Customer;

Auth::requireAuth();

$customerModel = new Customer();
$search = $_GET['search'] ?? '';

if ($search) {
    $customers = $customerModel->search($search);
} else {
    $customers = $customerModel->findAll();
}

view('customers/index', [
    'customers' => $customers,
    'search' => $search,
]);
