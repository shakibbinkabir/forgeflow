<?php
namespace ForgeFlow\Models;

class Message extends BaseModel
{
    protected $table = 'messages';

    public function findByOrder($orderId)
    {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as user_name 
            FROM messages m 
            LEFT JOIN users u ON m.user_id = u.id 
            WHERE m.order_id = ? 
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function getRecentMessages($limit = 10)
    {
            $limit = (int)$limit;
            $driver = $this->db->getAttribute(\PDO::ATTR_DRIVER_NAME);
            if ($driver === 'mysql') {
                $sql = "
                    SELECT m.*, o.order_number, u.name as user_name 
                    FROM messages m 
                    JOIN orders o ON m.order_id = o.id 
                    LEFT JOIN users u ON m.user_id = u.id 
                    ORDER BY m.created_at DESC 
                    LIMIT {$limit}
                ";
                $stmt = $this->db->query($sql);
                return $stmt->fetchAll();
            } else {
                $stmt = $this->db->prepare("
                    SELECT m.*, o.order_number, u.name as user_name 
                    FROM messages m 
                    JOIN orders o ON m.order_id = o.id 
                    LEFT JOIN users u ON m.user_id = u.id 
                    ORDER BY m.created_at DESC 
                    LIMIT ?
                ");
                $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll();
            }
    }

    public function createMessage($orderId, $message, $userId = null, $customerName = null, $isFromCustomer = false)
    {
        return $this->create([
            'order_id' => $orderId,
            'message' => $message,
            'user_id' => $userId,
            'customer_name' => $customerName,
            'is_from_customer' => $isFromCustomer ? 1 : 0,
        ]);
    }
}