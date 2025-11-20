<?php
class UserController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $includeDeleted = isset($_GET['include_deleted']) && $_GET['include_deleted'] === '1';
        $where = $includeDeleted ? "1=1" : "deleted_at IS NULL";
        $stmt = $this->pdo->query("SELECT * FROM users WHERE $where ORDER BY name");
        $users = $stmt->fetchAll();

        require '../views/layouts/header.php';
        require '../views/users/index.php';
        require '../views/layouts/footer.php';
    }

    public function create() {
        require '../views/layouts/header.php';
        require '../views/users/create.php';
        require '../views/layouts/footer.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf($_POST['csrf_token'] ?? '')) {
                $error = "Sessão expirada. Recarregue a página.";
                require '../views/layouts/header.php';
                require '../views/users/create.php';
                require '../views/layouts/footer.php';
                return;
            }

            $name = trim($_POST['name']);
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
            $level = $_POST['level'];
            $registration = trim($_POST['registration']);
            $sector = trim($_POST['sector']);
            $status = 'active';

            if (!$email || !$password || $name === '') {
                $error = "Nome, email válido e senha são obrigatórios.";
                require '../views/layouts/header.php';
                require '../views/users/create.php';
                require '../views/layouts/footer.php';
                return;
            }

            $sql = "INSERT INTO users (name, email, password, level, registration, sector, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            
            try {
                $stmt->execute([$name, $email, $password, $level, $registration, $sector, $status]);
                $userId = $this->pdo->lastInsertId();
                audit_log($this->pdo, 'user_created', 'user', $userId, ['email' => $email, 'level' => $level]);
                redirect('?route=users');
            } catch (PDOException $e) {
                $error = "Erro ao cadastrar usuário: " . $e->getMessage();
                require '../views/layouts/header.php';
                require '../views/users/create.php';
                require '../views/layouts/footer.php';
            }
        }
    }

    public function edit($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) {
            redirect('?route=users');
        }

        require '../views/layouts/header.php';
        require '../views/users/edit.php';
        require '../views/layouts/footer.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf($_POST['csrf_token'] ?? '')) {
                $error = "Sessão expirada. Recarregue a página.";
                $this->edit($_POST['id']);
                return;
            }

            $id = $_POST['id'];
            $name = trim($_POST['name']);
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            $level = $_POST['level'];
            $registration = trim($_POST['registration']);
            $sector = trim($_POST['sector']);
            $status = $_POST['status'];

            if (!$email || $name === '') {
                $error = "Nome e email válido são obrigatórios.";
                $this->edit($id);
                return;
            }

            $sql = "UPDATE users SET name = ?, email = ?, level = ?, registration = ?, sector = ?, status = ? WHERE id = ?";
            $params = [$name, $email, $level, $registration, $sector, $status, $id];

            // Se a senha foi preenchida, atualiza
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql = "UPDATE users SET name = ?, email = ?, level = ?, registration = ?, sector = ?, status = ?, password = ? WHERE id = ?";
                $params = [$name, $email, $level, $registration, $sector, $status, $password, $id];
            }

            $stmt = $this->pdo->prepare($sql);
            
            try {
                $stmt->execute($params);
                audit_log($this->pdo, 'user_updated', 'user', $id, ['email' => $email, 'level' => $level, 'status' => $status]);
                redirect('?route=users');
            } catch (PDOException $e) {
                $error = "Erro ao atualizar usuário: " . $e->getMessage();
                // Recarrega a view de edição com erro
                $this->edit($id); 
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('?route=users');
        }
        $id = $id ?? ($_POST['id'] ?? null);
        if (!$id) {
            redirect('?route=users');
        }
        if (!validate_csrf($_POST['csrf_token'] ?? '')) {
            app_log('CSRF falhou ao deletar usuário', ['id' => $id]);
            redirect('?route=users');
        }
        $stmt = $this->pdo->prepare("UPDATE users SET deleted_at = NOW(), status = 'inactive' WHERE id = ?");
        $stmt->execute([$id]);
        audit_log($this->pdo, 'user_archived', 'user', $id, []);
        redirect('?route=users');
    }

    public function restore($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('?route=users');
        }
        $id = $id ?? ($_POST['id'] ?? null);
        if (!$id) {
            redirect('?route=users');
        }
        if (!validate_csrf($_POST['csrf_token'] ?? '')) {
            app_log('CSRF falhou ao restaurar usuário', ['id' => $id]);
            redirect('?route=users');
        }
        $stmt = $this->pdo->prepare("UPDATE users SET deleted_at = NULL, status = 'active' WHERE id = ?");
        $stmt->execute([$id]);
        audit_log($this->pdo, 'user_restored', 'user', $id, []);
        redirect('?route=users');
    }
}
