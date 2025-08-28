<?php
namespace ForgeFlow\Models;

class Customer extends BaseModel
{
    protected $table = 'customers';

    public function findByPhone($phone)
    {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE phone = ?");
        $stmt->execute([$phone]);
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function getOrderHistory($customerId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM orders 
            WHERE customer_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }

    public function search($term)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM customers 
            WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? 
            ORDER BY name
        ");
        $searchTerm = '%' . $term . '%';
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
}