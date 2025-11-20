<?php
class ReportController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Período padrão: último mês
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        // Estatísticas Gerais
        $stats = $this->getGeneralStats($start_date, $end_date);
        
        // Ferramentas mais emprestadas
        $topTools = $this->getTopTools($start_date, $end_date);
        
        // Usuários mais ativos
        $topUsers = $this->getTopUsers($start_date, $end_date);
        
        // Empréstimos por status
        $loansByStatus = $this->getLoansByStatus($start_date, $end_date);

        require '../views/layouts/header.php';
        require '../views/reports/index.php';
        require '../views/layouts/footer.php';
    }

    private function getGeneralStats($start_date, $end_date) {
        $stats = [];

        // Total de empréstimos no período
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM loans WHERE DATE(loan_date) BETWEEN ? AND ?");
        $stmt->execute([$start_date, $end_date]);
        $stats['total_loans'] = $stmt->fetchColumn();

        // Empréstimos abertos
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM loans WHERE status = 'open' AND DATE(loan_date) BETWEEN ? AND ?");
        $stmt->execute([$start_date, $end_date]);
        $stats['open_loans'] = $stmt->fetchColumn();

        // Empréstimos fechados
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM loans WHERE status = 'closed' AND DATE(loan_date) BETWEEN ? AND ?");
        $stmt->execute([$start_date, $end_date]);
        $stats['closed_loans'] = $stmt->fetchColumn();

        // Total de ferramentas emprestadas
        $stmt = $this->pdo->prepare("
            SELECT COUNT(DISTINCT li.tool_id) 
            FROM loan_items li 
            JOIN loans l ON li.loan_id = l.id 
            WHERE DATE(l.loan_date) BETWEEN ? AND ?
        ");
        $stmt->execute([$start_date, $end_date]);
        $stats['tools_loaned'] = $stmt->fetchColumn();

        // Como não temos mais previsão de devolução, vamos mostrar empréstimos abertos há mais de 7 dias
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM loans 
            WHERE status = 'open' 
            AND DATEDIFF(CURDATE(), loan_date) > 7
            AND DATE(loan_date) BETWEEN ? AND ?
        ");
        $stmt->execute([$start_date, $end_date]);
        $stats['overdue_loans'] = $stmt->fetchColumn();

        return $stats;
    }

    private function getTopTools($start_date, $end_date, $limit = 10) {
        $limit = (int)$limit;
        $sql = "
            SELECT t.code, t.description, COUNT(li.id) as loan_count
            FROM loan_items li
            JOIN tools t ON li.tool_id = t.id
            JOIN loans l ON li.loan_id = l.id
            WHERE DATE(l.loan_date) BETWEEN ? AND ?
            GROUP BY t.id
            ORDER BY loan_count DESC
            LIMIT $limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$start_date, $end_date]);
        return $stmt->fetchAll();
    }

    private function getTopUsers($start_date, $end_date, $limit = 10) {
        $limit = (int)$limit;
        $sql = "
            SELECT u.name, u.registration, u.sector, COUNT(l.id) as loan_count
            FROM loans l
            JOIN users u ON l.user_id = u.id
            WHERE DATE(l.loan_date) BETWEEN ? AND ?
            GROUP BY u.id
            ORDER BY loan_count DESC
            LIMIT $limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$start_date, $end_date]);
        return $stmt->fetchAll();
    }

    private function getLoansByStatus($start_date, $end_date) {
        $sql = "
            SELECT status, COUNT(*) as count
            FROM loans
            WHERE DATE(loan_date) BETWEEN ? AND ?
            GROUP BY status
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$start_date, $end_date]);
        return $stmt->fetchAll();
    }

    private function getAverageLoanTime($start_date, $end_date) {
        $sql = "
            SELECT AVG(DATEDIFF(
                COALESCE(
                    (SELECT MIN(return_date) FROM loan_items WHERE loan_id = l.id AND return_date IS NOT NULL),
                    CURDATE()
                ),
                l.loan_date
            )) as avg_days
            FROM loans l
            WHERE DATE(l.loan_date) BETWEEN ? AND ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$start_date, $end_date]);
        $result = $stmt->fetchColumn();
        return round($result, 1);
    }
}
