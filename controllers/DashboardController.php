<?php
class DashboardController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Coletar estatísticas gerais
        $stats = [
            'total_tools' => $this->pdo->query("SELECT COUNT(*) FROM tools WHERE deleted_at IS NULL")->fetchColumn(),
            'available_tools' => $this->pdo->query("SELECT COUNT(*) FROM tools WHERE status = 'available' AND deleted_at IS NULL")->fetchColumn(),
            'borrowed_tools' => $this->pdo->query("SELECT COUNT(*) FROM tools WHERE status = 'borrowed' AND deleted_at IS NULL")->fetchColumn(),
            'maintenance_tools' => $this->pdo->query("SELECT COUNT(*) FROM tools WHERE status = 'maintenance' AND deleted_at IS NULL")->fetchColumn(),
            'active_users' => $this->pdo->query("SELECT COUNT(*) FROM users WHERE status = 'active' AND deleted_at IS NULL")->fetchColumn(),
        ];

        // Dados específicos para usuários comuns
        $userLoans = [];
        $userStats = [];
        
        if ($_SESSION['user_level'] == 'user') {
            $userId = $_SESSION['user_id'];
            
            // Histórico de empréstimos do usuário
            $stmt = $this->pdo->prepare("
                SELECT l.*, 
                    (SELECT COUNT(*) FROM loan_items WHERE loan_id = l.id) as items_count,
                    op.name as operator_name
                FROM loans l
                JOIN users op ON l.operator_id = op.id
                WHERE l.user_id = ?
                ORDER BY l.loan_date DESC
                LIMIT 10
            ");
            $stmt->execute([$userId]);
            $userLoans = $stmt->fetchAll();
            
            // Estatísticas do usuário
            // Empréstimos
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM loans WHERE user_id = ?");
            $stmt->execute([$userId]);
            $userStats['total_loans'] = $stmt->fetchColumn();
            
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM loans WHERE user_id = ? AND status = 'open'");
            $stmt->execute([$userId]);
            $userStats['active_loans'] = $stmt->fetchColumn();
            
            // Ferramentas distintas já usadas
            $stmt = $this->pdo->prepare("
                SELECT COUNT(DISTINCT li.tool_id) 
                FROM loan_items li 
                JOIN loans l ON li.loan_id = l.id 
                WHERE l.user_id = ?
            ");
            $stmt->execute([$userId]);
            $userStats['tools_used'] = $stmt->fetchColumn();

            // Quantidade de itens retirados (total)
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(li.quantity),0)
                FROM loan_items li
                JOIN loans l ON li.loan_id = l.id
                WHERE l.user_id = ?
            ");
            $stmt->execute([$userId]);
            $userStats['total_items_taken'] = (int)$stmt->fetchColumn();

            // Quantidade de itens ainda a devolver (em empréstimos abertos)
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(li.quantity),0)
                FROM loan_items li
                JOIN loans l ON li.loan_id = l.id
                WHERE l.user_id = ? AND l.status = 'open'
            ");
            $stmt->execute([$userId]);
            $userStats['items_to_return'] = (int)$stmt->fetchColumn();
        }

        require '../views/layouts/header.php';
        require '../views/dashboard/index.php';
        require '../views/layouts/footer.php';
    }
}
