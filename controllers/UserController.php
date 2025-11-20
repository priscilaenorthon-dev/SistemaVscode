<?php
class UserController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY name");
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
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $level = $_POST['level'];
            $registration = $_POST['registration'];
            $sector = $_POST['sector'];
            $status = 'active';

            $sql = "INSERT INTO users (name, email, password, level, registration, sector, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            
            try {
                $stmt->execute([$name, $email, $password, $level, $registration, $sector, $status]);
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
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
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
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $level = $_POST['level'];
            $registration = $_POST['registration'];
            $sector = $_POST['sector'];
            $status = $_POST['status'];

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
                redirect('?route=users');
            } catch (PDOException $e) {
                $error = "Erro ao atualizar usuário: " . $e->getMessage();
                // Recarrega a view de edição com erro
                $this->edit($id); 
            }
        }
    }
}
