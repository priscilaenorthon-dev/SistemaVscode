<?php
class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf($_POST['csrf_token'] ?? '')) {
                $error = "Sessão expirada. Tente novamente.";
                require '../views/auth/login.php';
                return;
            }

            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active' AND deleted_at IS NULL");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_level'] = $user['level'];
                
                redirect('?route=dashboard');
            } else {
                $error = "Credenciais inválidas ou usuário inativo.";
                app_log('Falha no login', ['email' => $email]);
                require '../views/auth/login.php';
            }
        } else {
            require '../views/auth/login.php';
        }
    }

    public function logout() {
        session_destroy();
        redirect('?route=login');
    }
}
