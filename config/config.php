<?php
// Configurações gerais do sistema

// Define o timezone para o Brasil
date_default_timezone_set('America/Sao_Paulo');

// Detecta a URL base automaticamente (com fallback para CLI)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script = $_SERVER['SCRIPT_NAME'] ?? '/';
$path = dirname($script);

// Se estiver na raiz, remove a barra extra
if ($path == '/' || $path == '\\') {
    $path = '';
}

define('BASE_URL', $protocol . "://" . $host . $path);

// Inicia a sessão se não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Caminho e configuração de log centralizado
define('LOG_PATH', __DIR__ . '/../logs/app.log');
if (!is_dir(dirname(LOG_PATH))) {
    mkdir(dirname(LOG_PATH), 0777, true);
}
ini_set('log_errors', '1');
ini_set('error_log', LOG_PATH);

function app_log($message, array $context = []) {
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message;
    if (!empty($context)) {
        $line .= ' ' . json_encode($context);
    }
    $line .= PHP_EOL;
    file_put_contents(LOG_PATH, $line, FILE_APPEND);
}

// Auditoria estruturada
function audit_log(PDO $pdo, $action, $entity, $entityId, array $details = []) {
    try {
        $userId = $_SESSION['user_id'] ?? null;
        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, entity, entity_id, details, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $userId,
            $action,
            $entity,
            $entityId,
            json_encode($details, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        ]);
    } catch (Throwable $e) {
        app_log('Falha ao registrar auditoria', ['error' => $e->getMessage()]);
    }
}

// CSRF helpers
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function validate_csrf($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        app_log('CSRF validation failed', ['ip' => $_SERVER['REMOTE_ADDR'] ?? 'cli']);
        return false;
    }
    return true;
}

// Função auxiliar para redirecionamento
function redirect($url) {
    header("Location: " . BASE_URL . "/" . $url);
    exit;
}

// Função para verificar login
function checkAuth($levels = []) {
    if (!isset($_SESSION['user_id'])) {
        redirect('?route=login');
    }

    if (!empty($levels) && !in_array($_SESSION['user_level'], $levels)) {
        echo "Acesso negado. Você não tem permissão para acessar esta página.";
        exit;
    }
}
