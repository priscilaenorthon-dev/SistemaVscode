<?php
class LoanController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Listar empréstimos abertos por padrão
        $status = isset($_GET['status']) ? $_GET['status'] : 'open';
        
        $sql = "SELECT l.*, u.name as user_name, op.name as operator_name, 
                (SELECT COUNT(*) FROM loan_items li WHERE li.loan_id = l.id) as total_items
                FROM loans l
                JOIN users u ON l.user_id = u.id
                JOIN users op ON l.operator_id = op.id
                WHERE l.status = ?
                ORDER BY l.loan_date DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$status]);
        $loans = $stmt->fetchAll();

        require '../views/layouts/header.php';
        require '../views/loans/index.php';
        require '../views/layouts/footer.php';
    }

    public function create() {
        // Buscar usuários para o select
        $users = $this->pdo->query("SELECT * FROM users WHERE status = 'active' ORDER BY name")->fetchAll();
        
        // Buscar ferramentas disponíveis para seleção
        $availableTools = $this->pdo->query("
            SELECT t.*, 
                   tc.name as category_name,
                   tm.name as model_name 
            FROM tools t
            LEFT JOIN tool_categories tc ON t.category_id = tc.id
            LEFT JOIN tool_models tm ON t.model_id = tm.id
            WHERE t.available_quantity > 0 
            ORDER BY t.code
        ")->fetchAll();
        
        require '../views/layouts/header.php';
        require '../views/loans/create.php';
        require '../views/layouts/footer.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $tool_codes = $_POST['tool_codes']; // Formato: CODIGO:QTD CODIGO:QTD
            $operator_id = $_SESSION['user_id'];

            // Processar códigos e quantidades
            $items = preg_split('/[\s,]+/', $tool_codes, -1, PREG_SPLIT_NO_EMPTY);
            
            if (empty($items)) {
                $error = "Nenhuma ferramenta informada.";
                $users = $this->pdo->query("SELECT * FROM users WHERE status = 'active' ORDER BY name")->fetchAll();
                $availableTools = $this->pdo->query("SELECT t.*, c.name as category_name, m.name as model_name FROM tools t LEFT JOIN tool_categories c ON t.category_id = c.id LEFT JOIN tool_models m ON t.model_id = m.id WHERE t.available_quantity > 0 ORDER BY t.code")->fetchAll();
                require '../views/layouts/header.php';
                require '../views/loans/create.php';
                require '../views/layouts/footer.php';
                return;
            }

            try {
                $this->pdo->beginTransaction();

                // Criar o empréstimo
                $stmt = $this->pdo->prepare("INSERT INTO loans (user_id, operator_id, status) VALUES (?, ?, 'open')");
                $stmt->execute([$user_id, $operator_id]);
                $loan_id = $this->pdo->lastInsertId();

                // Processar cada item
                $stmtTool = $this->pdo->prepare("SELECT id, available_quantity FROM tools WHERE code = ?");
                $stmtInsertItem = $this->pdo->prepare("INSERT INTO loan_items (loan_id, tool_id, quantity, status) VALUES (?, ?, ?, 'borrowed')");
                $stmtUpdateTool = $this->pdo->prepare("UPDATE tools SET available_quantity = available_quantity - ? WHERE id = ?");

                foreach ($items as $item) {
                    // Parse CODIGO:QTD
                    $parts = explode(':', $item);
                    $code = $parts[0];
                    $quantity = isset($parts[1]) ? (int)$parts[1] : 1;

                    $stmtTool->execute([$code]);
                    $tool = $stmtTool->fetch();

                    if (!$tool) {
                        throw new Exception("Ferramenta com código '$code' não encontrada.");
                    }
                    if ($tool['available_quantity'] < $quantity) {
                        throw new Exception("Quantidade insuficiente para '$code'. Disponível: {$tool['available_quantity']}, Solicitado: $quantity");
                    }

                    // Inserir item com quantidade
                    $stmtInsertItem->execute([$loan_id, $tool['id'], $quantity]);
                    
                    // Atualizar quantidade disponível
                    $stmtUpdateTool->execute([$quantity, $tool['id']]);
                }

                $this->pdo->commit();
                
                redirect('?route=loans');

            } catch (Exception $e) {
                $this->pdo->rollBack();
                $error = "Erro ao registrar empréstimo: " . $e->getMessage();
                $users = $this->pdo->query("SELECT * FROM users WHERE status = 'active' ORDER BY name")->fetchAll();
                $availableTools = $this->pdo->query("SELECT t.*, c.name as category_name, m.name as model_name FROM tools t LEFT JOIN tool_categories c ON t.category_id = c.id LEFT JOIN tool_models m ON t.model_id = m.id WHERE t.available_quantity > 0 ORDER BY t.code")->fetchAll();
                require '../views/layouts/header.php';
                require '../views/loans/create.php';
                require '../views/layouts/footer.php';
            }
        }
    }

    public function returnLoan($id) {
        // Detalhes do empréstimo para devolução
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.name as user_name 
            FROM loans l 
            JOIN users u ON l.user_id = u.id 
            WHERE l.id = ?
        ");
        $stmt->execute([$id]);
        $loan = $stmt->fetch();

        if (!$loan) redirect('?route=loans');

        // Itens do empréstimo
        $stmtItems = $this->pdo->prepare("
            SELECT li.*, t.code, t.description, t.id as tool_real_id
            FROM loan_items li
            JOIN tools t ON li.tool_id = t.id
            WHERE li.loan_id = ?
        ");
        $stmtItems->execute([$id]);
        $items = $stmtItems->fetchAll();

        require '../views/layouts/header.php';
        require '../views/loans/return.php';
        require '../views/layouts/footer.php';
    }

    public function confirmReturn() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loan_id = $_POST['loan_id'];
            $items_status = $_POST['items']; // Array [item_id => condition]
            
            try {
                $this->pdo->beginTransaction();
                
                $allReturned = true;

                foreach ($items_status as $item_id => $condition) {
                    // Buscar tool_id e quantidade
                    $stmtGetItem = $this->pdo->prepare("SELECT tool_id, quantity FROM loan_items WHERE id = ?");
                    $stmtGetItem->execute([$item_id]);
                    $item = $stmtGetItem->fetch();
                    
                    // Atualizar item do empréstimo
                    $stmtUpdateItem = $this->pdo->prepare("UPDATE loan_items SET return_date = NOW(), return_condition = ?, status = 'returned' WHERE id = ?");
                    $stmtUpdateItem->execute([$condition, $item_id]);

                    // Devolver quantidade ao estoque
                    // Se a condição indicar dano, não devolve ao estoque disponível
                    if (stripos($condition, 'danificada') === false && stripos($condition, 'quebrada') === false) {
                        $stmtUpdateTool = $this->pdo->prepare("UPDATE tools SET available_quantity = available_quantity + ? WHERE id = ?");
                        $stmtUpdateTool->execute([$item['quantity'], $item['tool_id']]);
                    } else {
                        // Se danificada, apenas devolve mas pode marcar para manutenção manualmente depois
                        // Por enquanto só devolve a quantidade
                        $stmtUpdateTool = $this->pdo->prepare("UPDATE tools SET available_quantity = available_quantity + ? WHERE id = ?");
                        $stmtUpdateTool->execute([$item['quantity'], $item['tool_id']]);
                    }
                }

                // Verificar se todos os itens deste empréstimo foram devolvidos
                $stmtCheck = $this->pdo->prepare("SELECT COUNT(*) FROM loan_items WHERE loan_id = ? AND status = 'borrowed'");
                $stmtCheck->execute([$loan_id]);
                $remaining = $stmtCheck->fetchColumn();

                if ($remaining == 0) {
                    // Fechar empréstimo
                    $stmtClose = $this->pdo->prepare("UPDATE loans SET status = 'closed' WHERE id = ?");
                    $stmtClose->execute([$loan_id]);
                }

                $this->pdo->commit();
                redirect('?route=loans');

            } catch (Exception $e) {
                $this->pdo->rollBack();
                echo "Erro ao registrar devolução: " . $e->getMessage();
            }
        }
    }

    public function printTerm($id) {
        // Buscar dados do empréstimo
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.name as user_name, u.registration, u.sector, op.name as operator_name 
            FROM loans l 
            JOIN users u ON l.user_id = u.id 
            JOIN users op ON l.operator_id = op.id
            WHERE l.id = ?
        ");
        $stmt->execute([$id]);
        $loan = $stmt->fetch();

        if (!$loan) redirect('?route=loans');

        // Buscar itens
        $stmtItems = $this->pdo->prepare("
            SELECT t.code, t.description, t.serial_number
            FROM loan_items li
            JOIN tools t ON li.tool_id = t.id
            WHERE li.loan_id = ?
        ");
        $stmtItems->execute([$id]);
        $items = $stmtItems->fetchAll();

        // View de impressão (sem header/footer padrão)
        require '../views/loans/print.php';
    }
}
