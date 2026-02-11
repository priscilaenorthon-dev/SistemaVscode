<?php
class MaintenanceController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'pending';
        $success = isset($_GET['success']) ? $_GET['success'] : null;

        $where = "t.deleted_at IS NULL";
        $params = [];

        if ($statusFilter !== 'all') {
            $where .= " AND m.status = ?";
            $params[] = $statusFilter;
        }

        $sql = "SELECT m.*, t.code, t.description as tool_description
                FROM maintenance m
                JOIN tools t ON m.tool_id = t.id
                WHERE {$where}
                ORDER BY m.start_date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $maintenances = $stmt->fetchAll();

        require '../views/layouts/header.php';
        require '../views/maintenance/index.php';
        require '../views/layouts/footer.php';
    }

    public function start() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('?route=maintenance');
        }
        if (!validate_csrf($_POST['csrf_token'] ?? '')) {
            redirect('?route=maintenance');
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            redirect('?route=maintenance');
        }

        $stmt = $this->pdo->prepare("UPDATE maintenance SET status = 'in_progress' WHERE id = ? AND status = 'pending'");
        $stmt->execute([$id]);
        audit_log($this->pdo, 'maintenance_started', 'maintenance', $id, []);
        redirect('?route=maintenance&status=in_progress&success=Manutenção iniciada com sucesso.');
    }

    public function complete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('?route=maintenance');
        }
        if (!validate_csrf($_POST['csrf_token'] ?? '')) {
            redirect('?route=maintenance');
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            redirect('?route=maintenance');
        }

        $stmt = $this->pdo->prepare("SELECT tool_id FROM maintenance WHERE id = ?");
        $stmt->execute([$id]);
        $maintenance = $stmt->fetch();

        if (!$maintenance) {
            redirect('?route=maintenance');
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("UPDATE maintenance SET status = 'completed', end_date = CURDATE() WHERE id = ?");
            $stmt->execute([$id]);

            // Verificar se há outras manutenções pendentes para esta ferramenta
            $stmtCheck = $this->pdo->prepare("SELECT COUNT(*) FROM maintenance WHERE tool_id = ? AND status IN ('pending', 'in_progress') AND id != ?");
            $stmtCheck->execute([$maintenance['tool_id'], $id]);
            $otherPending = $stmtCheck->fetchColumn();

            if ($otherPending == 0) {
                $stmtTool = $this->pdo->prepare("UPDATE tools SET status = 'available' WHERE id = ? AND status = 'maintenance'");
                $stmtTool->execute([$maintenance['tool_id']]);
            }

            $this->pdo->commit();
            audit_log($this->pdo, 'maintenance_completed', 'maintenance', $id, ['tool_id' => $maintenance['tool_id']]);
            redirect('?route=maintenance&status=completed&success=Manutenção concluída com sucesso.');
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            app_log('Erro ao concluir manutenção', ['error' => $e->getMessage()]);
            redirect('?route=maintenance');
        }
    }
}
