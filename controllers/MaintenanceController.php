<?php
class MaintenanceController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Listar ferramentas em manutenção ou calibração
        $sql = "SELECT m.*, t.code, t.description 
                FROM maintenance m
                JOIN tools t ON m.tool_id = t.id
                WHERE m.status = 'pending'
                ORDER BY m.scheduled_date ASC";
        
        $stmt = $this->pdo->query($sql);
        $maintenances = $stmt->fetchAll();

        require '../views/layouts/header.php';
        require '../views/maintenance/index.php';
        require '../views/layouts/footer.php';
    }
}
