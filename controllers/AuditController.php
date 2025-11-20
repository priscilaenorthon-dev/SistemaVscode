<?php
class AuditController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        $entity = isset($_GET['entity']) ? trim($_GET['entity']) : '';

        $where = "1=1";
        $params = [];

        if ($search !== '') {
            $where .= " AND (al.action LIKE ? OR al.entity LIKE ? OR al.details LIKE ?)";
            $like = "%" . $search . "%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if ($entity !== '') {
            $where .= " AND al.entity = ?";
            $params[] = $entity;
        }

        $sql = "
            SELECT al.*, u.name as user_name
            FROM audit_logs al
            LEFT JOIN users u ON al.user_id = u.id
            WHERE $where
            ORDER BY al.created_at DESC
            LIMIT 200
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $logs = $stmt->fetchAll();

        require '../views/layouts/header.php';
        require '../views/audit/index.php';
        require '../views/layouts/footer.php';
    }
}
