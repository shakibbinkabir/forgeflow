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
        $stmt = $this->db->prepare("
            SELECT o.*, c.name as customer_name, c.email as customer_email, c.phone as customer_phone 
            FROM orders o 
            JOIN customers c ON o.customer_id = c.id 
            WHERE o.status = ? 
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    public function findWithCustomer($id)
    {
        $stmt = $this->db->prepare("
            SELECT o.*, c.name as customer_name, c.email as customer_email, c.phone as customer_phone, c.address as customer_address 
            FROM orders o 
            JOIN customers c ON o.customer_id = c.id 
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findAllWithCustomer($filters = [])
    {
        $sql = "
            SELECT o.*, c.name as customer_name, c.email as customer_email, c.phone as customer_phone 
            FROM orders o 
            JOIN customers c ON o.customer_id = c.id
        ";
        
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
        $historyData = [
            'order_id' => $orderId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $userId,
            'notes' => $notes,
        ];
        
        $stmt = $this->db->prepare("
            INSERT INTO order_status_history (order_id, old_status, new_status, changed_by, notes, changed_at) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
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
        $stmt = $this->db->prepare("
            SELECT osh.*, u.name as changed_by_name 
            FROM order_status_history osh 
            LEFT JOIN users u ON osh.changed_by = u.id 
            WHERE osh.order_id = ? 
            ORDER BY osh.changed_at DESC
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function getStatusCounts()
    {
        $stmt = $this->db->query("
            SELECT status, COUNT(*) as count 
            FROM orders 
            GROUP BY status
        ");
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
        
        $stmt = $this->db->prepare("
            SELECT 
                strftime('%m', created_at) as month,
                COUNT(*) as count
            FROM orders 
            WHERE strftime('%Y', created_at) = ?
            GROUP BY strftime('%m', created_at)
            ORDER BY month
        ");
        $stmt->execute([$year]);
        
        $results = $stmt->fetchAll();
        $monthlyCounts = array_fill(1, 12, 0);
        
        foreach ($results as $row) {
            $monthlyCounts[(int)$row['month']] = (int)$row['count'];
        }
        
        return $monthlyCounts;
    }
}