<?php
require_once '../config/config.php';
require_once '../config/database.php';

// Roteamento simples
$route = isset($_GET['route']) ? $_GET['route'] : 'dashboard';

// Se não estiver logado e tentar acessar qualquer coisa que não seja login, redireciona
if (!isset($_SESSION['user_id']) && $route !== 'login' && $route !== 'auth_login') {
    $route = 'login';
}

// Mapeamento de rotas para controllers/actions
switch ($route) {
    case 'login':
        require_once '../views/auth/login.php';
        break;
    case 'auth_login':
        require_once '../controllers/AuthController.php';
        $controller = new AuthController($pdo);
        $controller->login();
        break;
    case 'logout':
        require_once '../controllers/AuthController.php';
        $controller = new AuthController($pdo);
        $controller->logout();
        break;
    case 'dashboard':
        checkAuth(); // Requer login
        require_once '../controllers/DashboardController.php';
        $controller = new DashboardController($pdo);
        $controller->index();
        break;
    
    // --- USUÁRIOS ---
    case 'users':
        checkAuth(['admin']);
        require_once '../controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->index();
        break;
    case 'users_create':
        checkAuth(['admin']);
        require_once '../controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->create();
        break;
    case 'users_store':
        checkAuth(['admin']);
        require_once '../controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->store();
        break;
    case 'users_edit':
        checkAuth(['admin']);
        require_once '../controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->edit($_GET['id']);
        break;
    case 'users_update':
        checkAuth(['admin']);
        require_once '../controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->update();
        break;
    
    // --- FERRAMENTAS ---
    case 'tools':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/ToolController.php';
        $controller = new ToolController($pdo);
        $controller->index();
        break;
    case 'tools_create':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/ToolController.php';
        $controller = new ToolController($pdo);
        $controller->create();
        break;
    case 'tools_store':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/ToolController.php';
        $controller = new ToolController($pdo);
        $controller->store();
        break;
    case 'tools_edit':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/ToolController.php';
        $controller = new ToolController($pdo);
        $controller->edit($_GET['id']);
        break;
    case 'tools_update':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/ToolController.php';
        $controller = new ToolController($pdo);
        $controller->update();
        break;
    case 'tools_view':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/ToolController.php';
        $controller = new ToolController($pdo);
        $controller->view($_GET['id']);
        break;

    // --- EMPRÉSTIMOS ---
    case 'loans':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/LoanController.php';
        $controller = new LoanController($pdo);
        $controller->index();
        break;
    case 'loans_create':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/LoanController.php';
        $controller = new LoanController($pdo);
        $controller->create();
        break;
    case 'loans_store':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/LoanController.php';
        $controller = new LoanController($pdo);
        $controller->store();
        break;
    case 'loans_return':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/LoanController.php';
        $controller = new LoanController($pdo);
        $controller->returnLoan($_GET['id']);
        break;
     case 'loans_confirm_return':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/LoanController.php';
        $controller = new LoanController($pdo);
        $controller->confirmReturn();
        break;
    case 'loans_print':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/LoanController.php';
        $controller = new LoanController($pdo);
        $controller->printTerm($_GET['id']);
        break;

    // --- RELATÓRIOS ---
    case 'reports':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/ReportController.php';
        $controller = new ReportController($pdo);
        $controller->index();
        break;

    // --- MANUTENÇÃO ---
    case 'maintenance':
        checkAuth(['admin', 'operator']);
        require_once '../controllers/MaintenanceController.php';
        $controller = new MaintenanceController($pdo);
        $controller->index();
        break;

    default:
        // 404
        echo "Página não encontrada.";
        break;
}
