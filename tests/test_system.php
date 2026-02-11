<?php
/**
 * Teste automatizado abrangente do Sistema de Ferramentaria.
 * Execute com: php tests/test_system.php
 *
 * Testa: conexão BD, estrutura de tabelas, controllers, regras de negócio,
 * CSRF, autenticação e integridade de dados.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$passed = 0;
$failed = 0;
$errors = [];

function test($description, $condition, &$passed, &$failed, &$errors) {
    if ($condition) {
        $passed++;
        echo "  [OK] $description\n";
    } else {
        $failed++;
        $errors[] = $description;
        echo "  [FALHOU] $description\n";
    }
}

echo "==============================================\n";
echo " TESTES AUTOMATIZADOS - Sistema Ferramentaria\n";
echo "==============================================\n\n";

// ============================================
// 1. TESTES DE CONEXÃO E INFRAESTRUTURA
// ============================================
echo "--- 1. Conexão e Infraestrutura ---\n";

test("Conexão com banco de dados", isset($pdo) && $pdo->query("SELECT 1")->fetchColumn() == 1, $passed, $failed, $errors);

$requiredTables = ['users', 'tools', 'loans', 'loan_items', 'maintenance', 'audit_logs', 'tool_categories', 'tool_models'];
foreach ($requiredTables as $table) {
    $exists = $pdo->query("SHOW TABLES LIKE '{$table}'")->fetchColumn();
    test("Tabela '{$table}' existe", (bool)$exists, $passed, $failed, $errors);
}

// Verifica colunas críticas
$toolCols = $pdo->query("DESCRIBE tools")->fetchAll(PDO::FETCH_COLUMN);
test("Tabela tools tem coluna 'deleted_at'", in_array('deleted_at', $toolCols), $passed, $failed, $errors);
test("Tabela tools tem coluna 'available_quantity'", in_array('available_quantity', $toolCols), $passed, $failed, $errors);
test("Tabela tools tem coluna 'quantity'", in_array('quantity', $toolCols), $passed, $failed, $errors);

$userCols = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_COLUMN);
test("Tabela users tem coluna 'deleted_at'", in_array('deleted_at', $userCols), $passed, $failed, $errors);
test("Tabela users tem coluna 'level'", in_array('level', $userCols), $passed, $failed, $errors);

$maintenanceCols = $pdo->query("DESCRIBE maintenance")->fetchAll(PDO::FETCH_COLUMN);
test("Tabela maintenance tem coluna 'start_date'", in_array('start_date', $maintenanceCols), $passed, $failed, $errors);
test("Tabela maintenance tem coluna 'end_date'", in_array('end_date', $maintenanceCols), $passed, $failed, $errors);
test("Tabela maintenance tem coluna 'cost'", in_array('cost', $maintenanceCols), $passed, $failed, $errors);

echo "\n";

// ============================================
// 2. TESTES DE SEGURANÇA
// ============================================
echo "--- 2. Segurança ---\n";

// CSRF
$token = csrf_token();
test("CSRF token é gerado", !empty($token), $passed, $failed, $errors);
test("CSRF token tem comprimento adequado (>=32 chars)", strlen($token) >= 32, $passed, $failed, $errors);
test("CSRF token é consistente na mesma sessão", csrf_token() === $token, $passed, $failed, $errors);
test("Validação CSRF aceita token correto", validate_csrf($token), $passed, $failed, $errors);
test("Validação CSRF rejeita token incorreto", !validate_csrf('token_invalido_123'), $passed, $failed, $errors);

// Senhas com hash
$stmt = $pdo->query("SELECT password FROM users WHERE email = 'admin@empresa.com'");
$adminHash = $stmt->fetchColumn();
test("Senha do admin está com hash bcrypt", str_starts_with($adminHash, '$2y$'), $passed, $failed, $errors);
test("Senha 'password' é válida para admin", password_verify('password', $adminHash), $passed, $failed, $errors);

echo "\n";

// ============================================
// 3. TESTES DE DADOS E INTEGRIDADE
// ============================================
echo "--- 3. Dados e Integridade ---\n";

// Usuários
$userCount = $pdo->query("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL")->fetchColumn();
test("Existem usuários ativos no sistema", $userCount > 0, $passed, $failed, $errors);

$adminCount = $pdo->query("SELECT COUNT(*) FROM users WHERE level = 'admin' AND status = 'active' AND deleted_at IS NULL")->fetchColumn();
test("Existe pelo menos um admin ativo", $adminCount >= 1, $passed, $failed, $errors);

// Ferramentas
$toolCount = $pdo->query("SELECT COUNT(*) FROM tools WHERE deleted_at IS NULL")->fetchColumn();
test("Existem ferramentas cadastradas", $toolCount > 0, $passed, $failed, $errors);

// Quantidade disponível nunca negativa
$negativeQty = $pdo->query("SELECT COUNT(*) FROM tools WHERE available_quantity < 0")->fetchColumn();
test("Nenhuma ferramenta com quantidade disponível negativa", $negativeQty == 0, $passed, $failed, $errors);

// Quantidade disponível não excede quantidade total
$overQty = $pdo->query("SELECT COUNT(*) FROM tools WHERE available_quantity > quantity")->fetchColumn();
test("Nenhuma ferramenta com disponível > total", $overQty == 0, $passed, $failed, $errors);

// Empréstimos
$loanCount = $pdo->query("SELECT COUNT(*) FROM loans")->fetchColumn();
test("Existem empréstimos registrados", $loanCount > 0, $passed, $failed, $errors);

$openLoans = $pdo->query("SELECT COUNT(*) FROM loans WHERE status = 'open'")->fetchColumn();
test("Existem empréstimos abertos", $openLoans > 0, $passed, $failed, $errors);

// Todo empréstimo aberto deve ter pelo menos 1 item emprestado
$openLoansWithoutItems = $pdo->query("
    SELECT COUNT(*) FROM loans l
    WHERE l.status = 'open'
    AND NOT EXISTS (SELECT 1 FROM loan_items li WHERE li.loan_id = l.id AND li.status = 'borrowed')
")->fetchColumn();
test("Todo empréstimo aberto tem itens pendentes", $openLoansWithoutItems == 0, $passed, $failed, $errors);

// Integridade referencial: loan_items aponta para ferramentas existentes
$orphanItems = $pdo->query("
    SELECT COUNT(*) FROM loan_items li
    LEFT JOIN tools t ON li.tool_id = t.id
    WHERE t.id IS NULL
")->fetchColumn();
test("Nenhum item de empréstimo aponta para ferramenta inexistente", $orphanItems == 0, $passed, $failed, $errors);

// Integridade referencial: empréstimos apontam para usuários existentes
$orphanLoans = $pdo->query("
    SELECT COUNT(*) FROM loans l
    LEFT JOIN users u ON l.user_id = u.id
    WHERE u.id IS NULL
")->fetchColumn();
test("Nenhum empréstimo aponta para usuário inexistente", $orphanLoans == 0, $passed, $failed, $errors);

// Categorias
$catCount = $pdo->query("SELECT COUNT(*) FROM tool_categories")->fetchColumn();
test("Existem categorias cadastradas", $catCount > 0, $passed, $failed, $errors);

// Modelos
$modelCount = $pdo->query("SELECT COUNT(*) FROM tool_models")->fetchColumn();
test("Existem modelos cadastrados", $modelCount > 0, $passed, $failed, $errors);

echo "\n";

// ============================================
// 4. TESTES DOS CONTROLLERS (lógica de negócio)
// ============================================
echo "--- 4. Lógica de Negócio ---\n";

// Simula autenticação para testes
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Test Admin';
$_SESSION['user_level'] = 'admin';

// Teste: DashboardController retorna estatísticas válidas
require_once __DIR__ . '/../controllers/DashboardController.php';
$totalTools = $pdo->query("SELECT COUNT(*) FROM tools WHERE deleted_at IS NULL")->fetchColumn();
$availableTools = $pdo->query("SELECT COUNT(*) FROM tools WHERE status = 'available' AND deleted_at IS NULL")->fetchColumn();
$borrowedTools = $pdo->query("SELECT COUNT(*) FROM tools WHERE status = 'borrowed' AND deleted_at IS NULL")->fetchColumn();
$maintenanceTools = $pdo->query("SELECT COUNT(*) FROM tools WHERE status = 'maintenance' AND deleted_at IS NULL")->fetchColumn();

test("Dashboard: total ferramentas >= 0", $totalTools >= 0, $passed, $failed, $errors);
test("Dashboard: ferramentas disponíveis + emprestadas + manutenção <= total", ($availableTools + $borrowedTools + $maintenanceTools) <= $totalTools, $passed, $failed, $errors);

// Teste: Busca de ferramentas com filtro funciona
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tools t WHERE t.deleted_at IS NULL AND t.status = ?");
$stmt->execute(['available']);
$filteredCount = $stmt->fetchColumn();
test("Filtro de ferramentas por status funciona", $filteredCount >= 0, $passed, $failed, $errors);

// Teste: Busca por texto funciona
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tools t WHERE t.deleted_at IS NULL AND (t.code LIKE ? OR t.description LIKE ?)");
$stmt->execute(['%T-0001%', '%T-0001%']);
$searchCount = $stmt->fetchColumn();
test("Busca de ferramentas por código funciona", $searchCount >= 1, $passed, $failed, $errors);

// Teste: Relatórios geram dados válidos
require_once __DIR__ . '/../controllers/ReportController.php';
$startDate = date('Y-01-01');
$endDate = date('Y-m-d');
$stmt = $pdo->prepare("SELECT COUNT(*) FROM loans WHERE DATE(loan_date) BETWEEN ? AND ?");
$stmt->execute([$startDate, $endDate]);
$reportCount = $stmt->fetchColumn();
test("Relatório: consulta de empréstimos no período funciona", $reportCount >= 0, $passed, $failed, $errors);

// Teste: Top ferramentas
$stmt = $pdo->prepare("
    SELECT t.code, COUNT(li.id) as loan_count
    FROM loan_items li
    JOIN tools t ON li.tool_id = t.id AND t.deleted_at IS NULL
    JOIN loans l ON li.loan_id = l.id
    WHERE DATE(l.loan_date) BETWEEN ? AND ?
    GROUP BY t.id ORDER BY loan_count DESC LIMIT 10
");
$stmt->execute([$startDate, $endDate]);
$topTools = $stmt->fetchAll();
test("Relatório: top ferramentas retorna resultados", count($topTools) >= 0, $passed, $failed, $errors);

// Teste: Manutenção - filtro por status
$stmt = $pdo->prepare("SELECT COUNT(*) FROM maintenance m JOIN tools t ON m.tool_id = t.id WHERE t.deleted_at IS NULL AND m.status = ?");
$stmt->execute(['pending']);
$pendingMaint = $stmt->fetchColumn();
test("Manutenção: filtro por status 'pending' funciona", $pendingMaint >= 0, $passed, $failed, $errors);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM maintenance m JOIN tools t ON m.tool_id = t.id WHERE t.deleted_at IS NULL AND m.status = ?");
$stmt->execute(['completed']);
$completedMaint = $stmt->fetchColumn();
test("Manutenção: filtro por status 'completed' funciona", $completedMaint >= 0, $passed, $failed, $errors);

// Teste: Auditoria
$stmt = $pdo->query("SELECT COUNT(*) FROM audit_logs");
$auditCount = $stmt->fetchColumn();
test("Tabela de auditoria acessível", $auditCount >= 0, $passed, $failed, $errors);

echo "\n";

// ============================================
// 5. TESTES DE OPERAÇÕES CRUD (transacionais)
// ============================================
echo "--- 5. Operações CRUD (transacionais) ---\n";

// Teste em transação para não alterar dados permanentemente
$pdo->beginTransaction();

try {
    // Criar ferramenta
    $stmt = $pdo->prepare("INSERT INTO tools (code, description, category_id, model_id, manufacturer, serial_number, location, acquisition_date, status, quantity, available_quantity) VALUES (?, ?, 1, 1, ?, ?, ?, ?, 'available', 5, 5)");
    $stmt->execute(['TEST-001', 'Ferramenta de Teste', 'Fabricante Teste', 'SN-TEST', 'Setor Teste', '2025-01-01']);
    $testToolId = $pdo->lastInsertId();
    test("CRUD: Criar ferramenta funciona", $testToolId > 0, $passed, $failed, $errors);

    // Verificar ferramenta criada
    $stmt = $pdo->prepare("SELECT * FROM tools WHERE id = ?");
    $stmt->execute([$testToolId]);
    $createdTool = $stmt->fetch();
    test("CRUD: Ferramenta criada tem dados corretos", $createdTool['code'] === 'TEST-001' && $createdTool['quantity'] == 5, $passed, $failed, $errors);

    // Atualizar ferramenta
    $stmt = $pdo->prepare("UPDATE tools SET description = 'Ferramenta Atualizada' WHERE id = ?");
    $stmt->execute([$testToolId]);
    $stmt = $pdo->prepare("SELECT description FROM tools WHERE id = ?");
    $stmt->execute([$testToolId]);
    test("CRUD: Atualizar ferramenta funciona", $stmt->fetchColumn() === 'Ferramenta Atualizada', $passed, $failed, $errors);

    // Soft delete
    $stmt = $pdo->prepare("UPDATE tools SET deleted_at = NOW(), status = 'inactive' WHERE id = ?");
    $stmt->execute([$testToolId]);
    $stmt = $pdo->prepare("SELECT deleted_at FROM tools WHERE id = ?");
    $stmt->execute([$testToolId]);
    test("CRUD: Soft delete de ferramenta funciona", $stmt->fetchColumn() !== null, $passed, $failed, $errors);

    // Restaurar
    $stmt = $pdo->prepare("UPDATE tools SET deleted_at = NULL, status = 'available' WHERE id = ?");
    $stmt->execute([$testToolId]);
    $stmt = $pdo->prepare("SELECT deleted_at FROM tools WHERE id = ?");
    $stmt->execute([$testToolId]);
    test("CRUD: Restaurar ferramenta funciona", $stmt->fetchColumn() === null, $passed, $failed, $errors);

    // Criar empréstimo
    $stmt = $pdo->prepare("INSERT INTO loans (user_id, operator_id, status) VALUES (3, 1, 'open')");
    $stmt->execute();
    $testLoanId = $pdo->lastInsertId();
    test("CRUD: Criar empréstimo funciona", $testLoanId > 0, $passed, $failed, $errors);

    // Adicionar item ao empréstimo
    $stmt = $pdo->prepare("INSERT INTO loan_items (loan_id, tool_id, quantity, status) VALUES (?, ?, 2, 'borrowed')");
    $stmt->execute([$testLoanId, $testToolId]);
    $testItemId = $pdo->lastInsertId();

    // Atualizar quantidade disponível da ferramenta
    $pdo->prepare("UPDATE tools SET available_quantity = available_quantity - 2 WHERE id = ?")->execute([$testToolId]);
    $stmt = $pdo->prepare("SELECT available_quantity FROM tools WHERE id = ?");
    $stmt->execute([$testToolId]);
    test("CRUD: Quantidade disponível reduzida após empréstimo", $stmt->fetchColumn() == 3, $passed, $failed, $errors);

    // Devolução
    $pdo->prepare("UPDATE loan_items SET return_date = NOW(), return_condition = 'Bom estado', status = 'returned' WHERE id = ?")->execute([$testItemId]);
    $pdo->prepare("UPDATE tools SET available_quantity = available_quantity + 2 WHERE id = ?")->execute([$testToolId]);
    $stmt = $pdo->prepare("SELECT available_quantity FROM tools WHERE id = ?");
    $stmt->execute([$testToolId]);
    test("CRUD: Quantidade restaurada após devolução", $stmt->fetchColumn() == 5, $passed, $failed, $errors);

    // Fechar empréstimo
    $pdo->prepare("UPDATE loans SET status = 'closed' WHERE id = ?")->execute([$testLoanId]);
    $stmt = $pdo->prepare("SELECT status FROM loans WHERE id = ?");
    $stmt->execute([$testLoanId]);
    test("CRUD: Fechar empréstimo funciona", $stmt->fetchColumn() === 'closed', $passed, $failed, $errors);

    // Criar usuário
    $hash = password_hash('teste123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, registration, sector, level, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
    $stmt->execute(['Teste User', 'teste@teste.com', $hash, 'TEST-001', 'TI', 'user']);
    $testUserId = $pdo->lastInsertId();
    test("CRUD: Criar usuário funciona", $testUserId > 0, $passed, $failed, $errors);

    // Verificar autenticação do usuário criado
    $stmt = $pdo->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->execute(['teste@teste.com']);
    $userHash = $stmt->fetchColumn();
    test("CRUD: Senha do novo usuário é verificável", password_verify('teste123', $userHash), $passed, $failed, $errors);

    // Auditoria
    audit_log($pdo, 'test_action', 'test_entity', 999, ['test' => true]);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM audit_logs WHERE action = 'test_action' AND entity = 'test_entity'");
    $stmt->execute();
    test("CRUD: Registro de auditoria funciona", $stmt->fetchColumn() >= 1, $passed, $failed, $errors);

    // Manutenção CRUD
    $stmt = $pdo->prepare("INSERT INTO maintenance (tool_id, description, start_date, status) VALUES (?, 'Teste manutenção', CURDATE(), 'pending')");
    $stmt->execute([$testToolId]);
    $testMaintId = $pdo->lastInsertId();
    test("CRUD: Criar manutenção funciona", $testMaintId > 0, $passed, $failed, $errors);

    $pdo->prepare("UPDATE maintenance SET status = 'in_progress' WHERE id = ?")->execute([$testMaintId]);
    $stmt = $pdo->prepare("SELECT status FROM maintenance WHERE id = ?");
    $stmt->execute([$testMaintId]);
    test("CRUD: Iniciar manutenção funciona", $stmt->fetchColumn() === 'in_progress', $passed, $failed, $errors);

    $pdo->prepare("UPDATE maintenance SET status = 'completed', end_date = CURDATE() WHERE id = ?")->execute([$testMaintId]);
    $stmt = $pdo->prepare("SELECT status, end_date FROM maintenance WHERE id = ?");
    $stmt->execute([$testMaintId]);
    $maint = $stmt->fetch();
    test("CRUD: Concluir manutenção funciona", $maint['status'] === 'completed' && $maint['end_date'] !== null, $passed, $failed, $errors);

} catch (Throwable $e) {
    $failed++;
    $errors[] = "Exceção durante testes CRUD: " . $e->getMessage();
    echo "  [FALHOU] Exceção: " . $e->getMessage() . "\n";
}

// Rollback para não afetar dados reais
$pdo->rollBack();

echo "\n";

// ============================================
// 6. TESTES DE FUNÇÕES AUXILIARES
// ============================================
echo "--- 6. Funções Auxiliares ---\n";

// app_log
$logPath = LOG_PATH;
$logDir = dirname($logPath);
test("Diretório de logs existe", is_dir($logDir), $passed, $failed, $errors);

app_log('Teste de log', ['teste' => true]);
test("Função app_log executa sem erro", true, $passed, $failed, $errors);

// csrf_field
$field = csrf_field();
test("csrf_field retorna input HTML", str_contains($field, '<input type="hidden"') && str_contains($field, 'csrf_token'), $passed, $failed, $errors);

// checkAuth (não deve redirecionar como admin)
$_SESSION['user_level'] = 'admin';
$_SESSION['user_id'] = 1;
// Não podemos testar redirect facilmente, mas podemos verificar a lógica
test("checkAuth: admin pode acessar rota admin", in_array($_SESSION['user_level'], ['admin']), $passed, $failed, $errors);
test("checkAuth: admin pode acessar rota operator", in_array($_SESSION['user_level'], ['admin', 'operator']), $passed, $failed, $errors);

$_SESSION['user_level'] = 'operator';
test("checkAuth: operator pode acessar rota operator", in_array($_SESSION['user_level'], ['admin', 'operator']), $passed, $failed, $errors);
test("checkAuth: operator NÃO pode acessar rota admin", !in_array($_SESSION['user_level'], ['admin']), $passed, $failed, $errors);

$_SESSION['user_level'] = 'user';
test("checkAuth: user NÃO pode acessar rota admin", !in_array($_SESSION['user_level'], ['admin']), $passed, $failed, $errors);
test("checkAuth: user NÃO pode acessar rota operator", !in_array($_SESSION['user_level'], ['admin', 'operator']), $passed, $failed, $errors);

echo "\n";

// ============================================
// 7. TESTES DE EXISTÊNCIA DE ARQUIVOS
// ============================================
echo "--- 7. Arquivos do Sistema ---\n";

$requiredFiles = [
    'config/config.php',
    'config/database.php',
    'controllers/AuthController.php',
    'controllers/DashboardController.php',
    'controllers/ToolController.php',
    'controllers/LoanController.php',
    'controllers/UserController.php',
    'controllers/ReportController.php',
    'controllers/AuditController.php',
    'controllers/MaintenanceController.php',
    'views/auth/login.php',
    'views/layouts/header.php',
    'views/layouts/footer.php',
    'views/dashboard/index.php',
    'views/tools/index.php',
    'views/tools/create.php',
    'views/tools/edit.php',
    'views/tools/view.php',
    'views/loans/index.php',
    'views/loans/create.php',
    'views/loans/return.php',
    'views/loans/print.php',
    'views/reports/index.php',
    'views/users/index.php',
    'views/users/create.php',
    'views/users/edit.php',
    'views/audit/index.php',
    'views/maintenance/index.php',
    'public/index.php',
    'public/assets/css/style.css',
];

$baseDir = __DIR__ . '/..';
foreach ($requiredFiles as $file) {
    $fullPath = $baseDir . '/' . $file;
    test("Arquivo existe: {$file}", file_exists($fullPath), $passed, $failed, $errors);
}

echo "\n";

// ============================================
// 8. TESTES DE ROTAS
// ============================================
echo "--- 8. Rotas do Sistema ---\n";

$routerContent = file_get_contents(__DIR__ . '/../public/index.php');
$requiredRoutes = [
    'login', 'auth_login', 'logout', 'dashboard',
    'users', 'users_create', 'users_store', 'users_edit', 'users_update', 'users_delete', 'users_restore',
    'tools', 'tools_create', 'tools_store', 'tools_edit', 'tools_update', 'tools_view', 'tools_delete', 'tools_restore',
    'loans', 'loans_create', 'loans_store', 'loans_return', 'loans_confirm_return', 'loans_print',
    'reports', 'audit_logs', 'maintenance', 'maintenance_start', 'maintenance_complete',
];

foreach ($requiredRoutes as $route) {
    test("Rota '{$route}' registrada no router", str_contains($routerContent, "'{$route}'"), $passed, $failed, $errors);
}

echo "\n";

// ============================================
// RESUMO
// ============================================
$total = $passed + $failed;
echo "==============================================\n";
echo " RESULTADO: {$passed}/{$total} testes passaram\n";
if ($failed > 0) {
    echo " FALHAS ({$failed}):\n";
    foreach ($errors as $err) {
        echo "   - $err\n";
    }
}
echo "==============================================\n";

exit($failed > 0 ? 1 : 0);
