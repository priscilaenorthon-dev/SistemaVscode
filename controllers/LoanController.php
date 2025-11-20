<?php
class LoanController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Listar empréstimos com filtro de status e busca
        $status = isset($_GET['status']) ? $_GET['status'] : 'open';
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';

        $params = [$status];
        $where = "l.status = ?";

        if ($search !== '') {
            $where .= " AND (l.id = ? OR u.name LIKE ? OR op.name LIKE ? OR l.loan_date LIKE ?)";
            $params[] = $search;
            $like = "%" . $search . "%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }
        
        $sql = "SELECT l.*, u.name as user_name, op.name as operator_name, 
                (SELECT COUNT(*) FROM loan_items li WHERE li.loan_id = l.id) as total_items
                FROM loans l
                JOIN users u ON l.user_id = u.id AND u.deleted_at IS NULL
                JOIN users op ON l.operator_id = op.id AND op.deleted_at IS NULL
                WHERE {$where}
                ORDER BY l.loan_date DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $loans = $stmt->fetchAll();

        require '../views/layouts/header.php';
        require '../views/loans/index.php';
        require '../views/layouts/footer.php';
    }

    public function create() {
        // Buscar usuários para o select
        $users = $this->pdo->query("SELECT * FROM users WHERE status = 'active' AND deleted_at IS NULL ORDER BY name")->fetchAll();
        
        // Buscar ferramentas disponíveis para seleção
        $availableTools = $this->pdo->query("
            SELECT t.*, 
                   tc.name as category_name,
                   tm.name as model_name 
            FROM tools t
            LEFT JOIN tool_categories tc ON t.category_id = tc.id
            LEFT JOIN tool_models tm ON t.model_id = tm.id
            WHERE t.available_quantity > 0 AND t.deleted_at IS NULL
            ORDER BY t.code
        ")->fetchAll();
        
        require '../views/layouts/header.php';
        require '../views/loans/create.php';
        require '../views/layouts/footer.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf($_POST['csrf_token'] ?? '')) {
                $error = "Sessão expirada. Recarregue a página.";
                $users = $this->pdo->query("SELECT * FROM users WHERE status = 'active' AND deleted_at IS NULL ORDER BY name")->fetchAll();
                $availableTools = $this->pdo->query("SELECT t.*, c.name as category_name, m.name as model_name FROM tools t LEFT JOIN tool_categories c ON t.category_id = c.id LEFT JOIN tool_models m ON t.model_id = m.id WHERE t.available_quantity > 0 AND t.deleted_at IS NULL ORDER BY t.code")->fetchAll();
                require '../views/layouts/header.php';
                require '../views/loans/create.php';
                require '../views/layouts/footer.php';
                return;
            }

            $user_id = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
            $tool_codes = $_POST['tool_codes']; // Formato: CODIGO:QTD CODIGO:QTD
            $operator_id = $_SESSION['user_id'];

            // Processar códigos e quantidades
            $items = preg_split('/[\s,]+/', $tool_codes, -1, PREG_SPLIT_NO_EMPTY);
            $items = array_filter($items); // remove entradas vazias

            if (!$user_id) {
                $error = "Selecione um colaborador válido.";
                $users = $this->pdo->query("SELECT * FROM users WHERE status = 'active' AND deleted_at IS NULL ORDER BY name")->fetchAll();
                $availableTools = $this->pdo->query("SELECT t.*, c.name as category_name, m.name as model_name FROM tools t LEFT JOIN tool_categories c ON t.category_id = c.id LEFT JOIN tool_models m ON t.model_id = m.id WHERE t.available_quantity > 0 AND t.deleted_at IS NULL ORDER BY t.code")->fetchAll();
                require '../views/layouts/header.php';
                require '../views/loans/create.php';
                require '../views/layouts/footer.php';
                return;
            }
            
            if (empty($items)) {
                $error = "Nenhuma ferramenta informada.";
                $users = $this->pdo->query("SELECT * FROM users WHERE status = 'active' AND deleted_at IS NULL ORDER BY name")->fetchAll();
                $availableTools = $this->pdo->query("SELECT t.*, c.name as category_name, m.name as model_name FROM tools t LEFT JOIN tool_categories c ON t.category_id = c.id LEFT JOIN tool_models m ON t.model_id = m.id WHERE t.available_quantity > 0 AND t.deleted_at IS NULL ORDER BY t.code")->fetchAll();
                require '../views/layouts/header.php';
                require '../views/loans/create.php';
                require '../views/layouts/footer.php';
                return;
            }

            try {
                // Valida usuário de destino
                $stmtUser = $this->pdo->prepare("SELECT id FROM users WHERE id = ? AND status = 'active' AND deleted_at IS NULL");
                $stmtUser->execute([$user_id]);
                if (!$stmtUser->fetch()) {
                    throw new Exception("Usuário inválido ou inativo.");
                }

                $this->pdo->beginTransaction();

                // Criar o empréstimo
                $stmt = $this->pdo->prepare("INSERT INTO loans (user_id, operator_id, status) VALUES (?, ?, 'open')");
                $stmt->execute([$user_id, $operator_id]);
                $loan_id = $this->pdo->lastInsertId();

                // Processar cada item
                $stmtTool = $this->pdo->prepare("SELECT id, available_quantity FROM tools WHERE code = ? AND deleted_at IS NULL");
                $stmtInsertItem = $this->pdo->prepare("INSERT INTO loan_items (loan_id, tool_id, quantity, status) VALUES (?, ?, ?, 'borrowed')");
                $stmtUpdateTool = $this->pdo->prepare("UPDATE tools SET available_quantity = available_quantity - ? WHERE id = ?");

                foreach ($items as $item) {
                    // Parse CODIGO:QTD
                    $parts = explode(':', $item);
                    $code = trim($parts[0]);
                    $quantity = isset($parts[1]) ? (int)$parts[1] : 1;
                    if ($code === '' || $quantity < 1) {
                        throw new Exception("Item de ferramenta inválido.");
                    }

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
                audit_log($this->pdo, 'loan_created', 'loan', $loan_id, ['user_id' => $user_id, 'items' => count($items)]);
                redirect('?route=loans');

            } catch (Exception $e) {
                $this->pdo->rollBack();
                $error = "Erro ao registrar empréstimo: " . $e->getMessage();
                app_log('Erro ao registrar empréstimo', ['error' => $e->getMessage()]);
                $users = $this->pdo->query("SELECT * FROM users WHERE status = 'active' AND deleted_at IS NULL ORDER BY name")->fetchAll();
                $availableTools = $this->pdo->query("SELECT t.*, c.name as category_name, m.name as model_name FROM tools t LEFT JOIN tool_categories c ON t.category_id = c.id LEFT JOIN tool_models m ON t.model_id = m.id WHERE t.available_quantity > 0 AND t.deleted_at IS NULL ORDER BY t.code")->fetchAll();
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
            JOIN users u ON l.user_id = u.id AND u.deleted_at IS NULL
            WHERE l.id = ?
        ");
        $stmt->execute([$id]);
        $loan = $stmt->fetch();

        if (!$loan) redirect('?route=loans');

        // Itens do empréstimo
        $stmtItems = $this->pdo->prepare("
            SELECT li.*, t.code, t.description, t.id as tool_real_id
            FROM loan_items li
            JOIN tools t ON li.tool_id = t.id AND t.deleted_at IS NULL
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
            if (!validate_csrf($_POST['csrf_token'] ?? '')) {
                redirect('?route=loans');
            }

            $loan_id = $_POST['loan_id'];
            $items_status = isset($_POST['items']) && is_array($_POST['items']) ? $_POST['items'] : [];
            if (empty($items_status)) {
                redirect('?route=loans');
            }

            try {
                $this->pdo->beginTransaction();
                
                $allReturned = true;
                $processed = 0;
                $damagedCount = 0;
                foreach ($items_status as $item_id => $payload) {
                    // Se não marcado para devolver, pula e mantém emprestado
                    if (isset($payload['confirm']) && $payload['confirm'] !== '1') {
                        continue;
                    }

                    $condition = $payload['condition'] ?? $payload; // suporte ao formato antigo
                    $returnQty = isset($payload['quantity']) ? (int)$payload['quantity'] : null;

                    // Buscar tool_id e quantidade
                    $stmtGetItem = $this->pdo->prepare("SELECT li.tool_id, li.quantity, t.quantity as total_quantity, t.available_quantity FROM loan_items li JOIN tools t ON li.tool_id = t.id WHERE li.id = ?");
                    $stmtGetItem->execute([$item_id]);
                    $item = $stmtGetItem->fetch();
                    if (!$item) {
                        throw new Exception("Item de devolução inválido.");
                    }

                    $borrowedQty = (int)$item['quantity'];
                    if ($returnQty === null) {
                        $returnQty = $borrowedQty;
                    }
                    if ($returnQty < 1 || $returnQty > $borrowedQty) {
                        throw new Exception("Quantidade devolvida inválida para o item {$item_id}.");
                    }

                    $isDamaged = preg_match('/danificad|quebrad|avariad|defeit|falha|dano/i', $condition);

                    // Quando devolve tudo
                    if ($returnQty === $borrowedQty) {
                        $stmtUpdateItem = $this->pdo->prepare("UPDATE loan_items SET return_date = NOW(), return_condition = ?, status = 'returned' WHERE id = ?");
                        $stmtUpdateItem->execute([$condition, $item_id]);
                    } else {
                        // Parcial: cria um registro de item devolvido e ajusta o original para o saldo em aberto
                        $stmtInsertPartial = $this->pdo->prepare("INSERT INTO loan_items (loan_id, tool_id, quantity, return_date, return_condition, status) VALUES (?, ?, ?, NOW(), ?, 'returned')");
                        $stmtInsertPartial->execute([$loan_id, $item['tool_id'], $returnQty, $condition]);

                        $remaining = $borrowedQty - $returnQty;
                        $stmtUpdateOriginal = $this->pdo->prepare("UPDATE loan_items SET quantity = ?, status = 'borrowed', return_date = NULL, return_condition = NULL WHERE id = ?");
                        $stmtUpdateOriginal->execute([$remaining, $item_id]);
                    }

                    if (!$isDamaged) {
                        $stmtUpdateTool = $this->pdo->prepare("UPDATE tools SET available_quantity = available_quantity + ? WHERE id = ?");
                        $stmtUpdateTool->execute([$returnQty, $item['tool_id']]);
                    } else {
                        // Retira do estoque disponível e marca para manutenção
                        $stmtDamage = $this->pdo->prepare("UPDATE tools SET status = 'maintenance', quantity = GREATEST(quantity - ?, 0), available_quantity = GREATEST(available_quantity - ?, 0) WHERE id = ?");
                        $stmtDamage->execute([$returnQty, $returnQty, $item['tool_id']]);

                        $stmtMaintenance = $this->pdo->prepare("INSERT INTO maintenance (tool_id, description, start_date, status) VALUES (?, ?, CURDATE(), 'pending')");
                        $stmtMaintenance->execute([$item['tool_id'], $condition]);
                        $damagedCount += $returnQty;
                    }
                    $processed += $returnQty;
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
                audit_log($this->pdo, 'loan_return', 'loan', $loan_id, ['processed_qty' => $processed, 'damaged_qty' => $damagedCount, 'open_items' => $remaining]);
                redirect('?route=loans');

            } catch (Exception $e) {
                $this->pdo->rollBack();
                app_log('Erro ao registrar devolução', ['error' => $e->getMessage()]);
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
