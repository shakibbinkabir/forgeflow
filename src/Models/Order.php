<?php
namespace ForgeFlow\Models;

class Order extends BaseModel
{
    protected $table = 'orders';
    
    const STATUS_PENDING = 'Pending';
    const STATUS_PROCESSING = 'Processing';
    const STATUS_PRINTING = 'Printing';
    const STATUS_POST_PROCESSING = 'Post-Processing';
    const STATUS_READY_FOR_STEADFAST = 'Ready for Steadfast';
    const STATUS_SHIPPED = 'Shipped';
    const STATUS_COMPLETED = 'Completed';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_PRINTING,
            self::STATUS_POST_PROCESSING,
            self::STATUS_READY_FOR_STEADFAST,
            self::STATUS_SHIPPED,
            self::STATUS_COMPLETED,
        ];
    }

    public function findByStatus($status)
    {
        $stmt = $this->db->prepare("\n            SELECT o.*, c.name as customer_name, c.email as customer_email, c.phone as customer_phone \n            FROM orders o \n            JOIN customers c ON o.customer_id = c.id \n            WHERE o.status = ? \n            ORDER BY o.created_at DESC\n        ");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    public function findWithCustomer($id)
    {
        $stmt = $this->db->prepare("\n            SELECT o.*, c.name as customer_name, c.email as customer_email, c.phone as customer_phone, c.address as customer_address \n            FROM orders o \n            JOIN customers c ON o.customer_id = c.id \n            WHERE o.id = ?\n        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findAllWithCustomer($filters = [])
    {
        $sql = "\n            SELECT o.*, c.name as customer_name, c.email as customer_email, c.phone as customer_phone \n            FROM orders o \n            JOIN customers c ON o.customer_id = c.id\n        ";
        
        $conditions = [];
        $params = [];
        
        if (!empty($filters['status'])) {
            $conditions[] = "o.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $conditions[] = "(o.order_number LIKE ? OR c.name LIKE ? OR o.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function generateOrderNumber()
    {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders WHERE order_number LIKE ?");
        $stmt->execute(["FF-{$year}-%"]);
        $count = $stmt->fetchColumn() + 1;
        
        return sprintf("FF-%s-%03d", $year, $count);
    }

    public function updateStatus($orderId, $newStatus, $userId = null, $notes = null)
    {
        // Get current status
        $order = $this->find($orderId);
        if (!$order) return false;
        
        $oldStatus = $order['status'];
        
        // Update order status
        $this->update($orderId, ['status' => $newStatus]);
        
        // Record status change in history
        $stmt = $this->db->prepare("\n            INSERT INTO order_status_history (order_id, old_status, new_status, changed_by, notes, changed_at) \n            VALUES (?, ?, ?, ?, ?, ?)\n        ");
        $stmt->execute([
            $orderId,
            $oldStatus,
            $newStatus,
            $userId,
            $notes,
            date('Y-m-d H:i:s')
        ]);
        
        return true;
    }

    public function getStatusHistory($orderId)
    {
        $stmt = $this->db->prepare("\n            SELECT osh.*, u.name as changed_by_name \n            FROM order_status_history osh \n            LEFT JOIN users u ON osh.changed_by = u.id \n            WHERE osh.order_id = ? \n            ORDER BY osh.changed_at DESC\n        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function getStatusCounts()
    {
        $stmt = $this->db->query("\n            SELECT status, COUNT(*) as count \n            FROM orders \n            GROUP BY status\n        ");
        $results = $stmt->fetchAll();
        
        $counts = [];
        foreach (self::getStatuses() as $status) {
            $counts[$status] = 0;
        }
        
        foreach ($results as $row) {
            $counts[$row['status']] = (int)$row['count'];
        }
        
        return $counts;
    }

    public function getMonthlyOrderCounts($year = null)
    {
        if (!$year) $year = date('Y');

        $driver = $this->db->getAttribute(\PDO::ATTR_DRIVER_NAME);
        if ($driver === 'mysql') {
            $sql = "
                SELECT 
                    MONTH(created_at) AS month,
                    COUNT(*) AS count
                FROM orders
                WHERE YEAR(created_at) = ?
                GROUP BY MONTH(created_at)
                ORDER BY month
            ";
        } else {
            $sql = "
                SELECT 
                    CAST(strftime('%m', created_at) AS INTEGER) AS month,
                    COUNT(*) AS count
                FROM orders 
                WHERE strftime('%Y', created_at) = ?
                GROUP BY strftime('%m', created_at)
                ORDER BY month
            ";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$year]);

        $results = $stmt->fetchAll();
        $monthlyCounts = array_fill(1, 12, 0);
        foreach ($results as $row) {
            $month = (int)$row['month'];
            if ($month >= 1 && $month <= 12) {
                $monthlyCounts[$month] = (int)$row['count'];
            }
        }
        return $monthlyCounts;
    }
}