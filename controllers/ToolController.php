<?php
class ToolController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Filtros avançados
        $where = "1=1";
        $params = [];
        $includeDeleted = isset($_GET['include_deleted']) && $_GET['include_deleted'] === '1';
        
        if (!empty($_GET['search'])) {
            $where .= " AND (t.code LIKE ? OR t.description LIKE ?)";
            $params[] = "%" . $_GET['search'] . "%";
            $params[] = "%" . $_GET['search'] . "%";
        }

        if (!empty($_GET['status'])) {
            $where .= " AND t.status = ?";
            $params[] = $_GET['status'];
        }
        
        if (!empty($_GET['category'])) {
            $where .= " AND t.category_id = ?";
            $params[] = $_GET['category'];
        }
        
        if (!empty($_GET['manufacturer'])) {
            $where .= " AND t.manufacturer LIKE ?";
            $params[] = "%" . $_GET['manufacturer'] . "%";
        }
        
        if (!empty($_GET['location'])) {
            $where .= " AND t.location LIKE ?";
            $params[] = "%" . $_GET['location'] . "%";
        }

        if (!$includeDeleted) {
            $where .= " AND t.deleted_at IS NULL";
        }
        
        // Ordenação
        $orderBy = "t.code";
        if (!empty($_GET['sort'])) {
            switch ($_GET['sort']) {
                case 'description':
                    $orderBy = "t.description";
                    break;
                case 'category':
                    $orderBy = "c.name";
                    break;
                case 'recent':
                    $orderBy = "t.created_at DESC";
                    break;
                default:
                    $orderBy = "t.code";
            }
        }

        $sql = "SELECT t.*, c.name as category_name, m.name as model_name 
                FROM tools t 
                LEFT JOIN tool_categories c ON t.category_id = c.id 
                LEFT JOIN tool_models m ON t.model_id = m.id 
                WHERE $where 
                ORDER BY $orderBy";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $tools = $stmt->fetchAll();

        require '../views/layouts/header.php';
        require '../views/tools/index.php';
        require '../views/layouts/footer.php';
    }

    public function create() {
        // Carregar categorias e modelos para o select
        $categories = $this->pdo->query("SELECT * FROM tool_categories ORDER BY name")->fetchAll();
        $models = $this->pdo->query("SELECT * FROM tool_models ORDER BY name")->fetchAll();

        require '../views/layouts/header.php';
        require '../views/tools/create.php';
        require '../views/layouts/footer.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf($_POST['csrf_token'] ?? '')) {
                $error = "Sessão expirada. Recarregue a página.";
                app_log('CSRF falhou ao criar ferramenta');
                $categories = $this->pdo->query("SELECT * FROM tool_categories ORDER BY name")->fetchAll();
                $models = $this->pdo->query("SELECT * FROM tool_models ORDER BY name")->fetchAll();
                require '../views/layouts/header.php';
                require '../views/tools/create.php';
                require '../views/layouts/footer.php';
                return;
            }

            $code = trim($_POST['code']);
            $description = trim($_POST['description']);
            $category_id = (int)$_POST['category_id'];
            $model_id = (int)$_POST['model_id'];
            $manufacturer = trim($_POST['manufacturer']);
            $serial_number = trim($_POST['serial_number']);
            $location = trim($_POST['location']);
            $acquisition_date = $_POST['acquisition_date'];
            $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
            $status = 'available';

            if ($code === '' || $description === '') {
                $error = "Código e descrição são obrigatórios.";
                $categories = $this->pdo->query("SELECT * FROM tool_categories ORDER BY name")->fetchAll();
                $models = $this->pdo->query("SELECT * FROM tool_models ORDER BY name")->fetchAll();
                require '../views/layouts/header.php';
                require '../views/tools/create.php';
                require '../views/layouts/footer.php';
                return;
            }

            $sql = "INSERT INTO tools (code, description, category_id, model_id, manufacturer, serial_number, location, acquisition_date, status, quantity, available_quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            
            try {
                $stmt->execute([$code, $description, $category_id, $model_id, $manufacturer, $serial_number, $location, $acquisition_date, $status, $quantity, $quantity]);
                $toolId = $this->pdo->lastInsertId();
                audit_log($this->pdo, 'tool_created', 'tool', $toolId, ['code' => $code, 'quantity' => $quantity]);
                redirect('?route=tools');
            } catch (PDOException $e) {
                $error = "Erro ao cadastrar ferramenta: " . $e->getMessage();
                // Recarregar dados para view
                $categories = $this->pdo->query("SELECT * FROM tool_categories ORDER BY name")->fetchAll();
                $models = $this->pdo->query("SELECT * FROM tool_models ORDER BY name")->fetchAll();
                require '../views/layouts/header.php';
                require '../views/tools/create.php';
                require '../views/layouts/footer.php';
            }
        }
    }

    public function edit($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tools WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        $tool = $stmt->fetch();

        if (!$tool) {
            redirect('?route=tools');
        }

        $categories = $this->pdo->query("SELECT * FROM tool_categories ORDER BY name")->fetchAll();
        $models = $this->pdo->query("SELECT * FROM tool_models ORDER BY name")->fetchAll();

        require '../views/layouts/header.php';
        require '../views/tools/edit.php';
        require '../views/layouts/footer.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf($_POST['csrf_token'] ?? '')) {
                $error = "Sessão expirada. Recarregue a página.";
                app_log('CSRF falhou ao atualizar ferramenta');
                $this->edit($_POST['id']);
                return;
            }

            $id = $_POST['id'];
            $code = trim($_POST['code']);
            $description = trim($_POST['description']);
            $category_id = (int)$_POST['category_id'];
            $model_id = (int)$_POST['model_id'];
            $manufacturer = trim($_POST['manufacturer']);
            $serial_number = trim($_POST['serial_number']);
            $location = trim($_POST['location']);
            $acquisition_date = $_POST['acquisition_date'];
            $status = $_POST['status'];
            $new_quantity = max(1, (int)$_POST['quantity']);

            // Buscar ferramenta atual para calcular available_quantity
            $stmt = $this->pdo->prepare("SELECT quantity, available_quantity FROM tools WHERE id = ?");
            $stmt->execute([$id]);
            $current = $stmt->fetch();
            
            // Calcular quantidade emprestada
            $borrowed = $current['quantity'] - $current['available_quantity'];
            
            // Nova quantidade disponível = nova quantidade total - quantidade emprestada
            $new_available = $new_quantity - $borrowed;
            
            // Validar se a nova quantidade é suficiente
            if ($new_available < 0) {
                $error = "A quantidade total não pode ser menor que a quantidade emprestada ($borrowed unidades).";
                $categories = $this->pdo->query("SELECT * FROM tool_categories ORDER BY name")->fetchAll();
                $models = $this->pdo->query("SELECT * FROM tool_models ORDER BY name")->fetchAll();
                $tool = $this->pdo->prepare("SELECT * FROM tools WHERE id = ?");
                $tool->execute([$id]);
                $tool = $tool->fetch();
                require '../views/layouts/header.php';
                require '../views/tools/edit.php';
                require '../views/layouts/footer.php';
                return;
            }

            $sql = "UPDATE tools SET code = ?, description = ?, category_id = ?, model_id = ?, manufacturer = ?, serial_number = ?, location = ?, acquisition_date = ?, status = ?, quantity = ?, available_quantity = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            
            try {
                $stmt->execute([$code, $description, $category_id, $model_id, $manufacturer, $serial_number, $location, $acquisition_date, $status, $new_quantity, $new_available, $id]);
                audit_log($this->pdo, 'tool_updated', 'tool', $id, ['code' => $code, 'quantity' => $new_quantity, 'available' => $new_available]);
                redirect('?route=tools');
            } catch (PDOException $e) {
                $error = "Erro ao atualizar ferramenta: " . $e->getMessage();
                $this->edit($id);
            }
        }
    }

    public function view($id) {
        // Buscar dados da ferramenta
        $stmt = $this->pdo->prepare("
            SELECT t.*, c.name as category_name, m.name as model_name 
            FROM tools t
            LEFT JOIN tool_categories c ON t.category_id = c.id
            LEFT JOIN tool_models m ON t.model_id = m.id
            WHERE t.id = ? AND t.deleted_at IS NULL
        ");
        $stmt->execute([$id]);
        $tool = $stmt->fetch();

        if (!$tool) {
            redirect('?route=tools');
        }

        // Buscar histórico de empréstimos
        $stmtHistory = $this->pdo->prepare("
            SELECT 
                l.id as loan_id,
                l.loan_date,
                l.status as loan_status,
                li.return_date,
                li.return_condition,
                li.status as item_status,
                li.quantity,
                u.name as user_name,
                u.registration,
                u.sector,
                op.name as operator_name
            FROM loan_items li
            JOIN loans l ON li.loan_id = l.id
            JOIN users u ON l.user_id = u.id
            JOIN users op ON l.operator_id = op.id
            WHERE li.tool_id = ?
            ORDER BY l.loan_date DESC
        ");
        $stmtHistory->execute([$id]);
        $loanHistory = $stmtHistory->fetchAll();

        // Estatísticas da ferramenta
        $stats = [];
        
        // Total de empréstimos
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM loan_items WHERE tool_id = ?");
        $stmt->execute([$id]);
        $stats['total_loans'] = $stmt->fetchColumn();

        // Empréstimos ativos
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM loan_items WHERE tool_id = ? AND status = 'borrowed'");
        $stmt->execute([$id]);
        $stats['active_loans'] = $stmt->fetchColumn();

        // Tempo médio de empréstimo
        $stmt = $this->pdo->prepare("
            SELECT AVG(DATEDIFF(return_date, l.loan_date)) as avg_days
            FROM loan_items li
            JOIN loans l ON li.loan_id = l.id
            WHERE li.tool_id = ? AND li.return_date IS NOT NULL
        ");
        $stmt->execute([$id]);
        $stats['avg_loan_days'] = round($stmt->fetchColumn(), 1);

        require '../views/layouts/header.php';
        require '../views/tools/view.php';
        require '../views/layouts/footer.php';
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('?route=tools');
        }
        $id = $id ?? ($_POST['id'] ?? null);
        if (!$id) {
            redirect('?route=tools');
        }
        if (!validate_csrf($_POST['csrf_token'] ?? '')) {
            app_log('CSRF falhou ao deletar ferramenta', ['id' => $id]);
            redirect('?route=tools');
        }

        $stmt = $this->pdo->prepare("UPDATE tools SET deleted_at = NOW(), status = 'inactive' WHERE id = ?");
        $stmt->execute([$id]);
        audit_log($this->pdo, 'tool_archived', 'tool', $id, []);
        redirect('?route=tools');
    }

    public function restore($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('?route=tools');
        }
        $id = $id ?? ($_POST['id'] ?? null);
        if (!$id) {
            redirect('?route=tools');
        }
        if (!validate_csrf($_POST['csrf_token'] ?? '')) {
            app_log('CSRF falhou ao restaurar ferramenta', ['id' => $id]);
            redirect('?route=tools&include_deleted=1');
        }

        $stmt = $this->pdo->prepare("UPDATE tools SET deleted_at = NULL, status = 'available' WHERE id = ?");
        $stmt->execute([$id]);
        audit_log($this->pdo, 'tool_restored', 'tool', $id, []);
        redirect('?route=tools');
    }
}
