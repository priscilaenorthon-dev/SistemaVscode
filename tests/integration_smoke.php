<?php
/**
 * Smoke test rápido para validar pré-requisitos de execução.
 * Execute com: php tests/integration_smoke.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$errors = [];

// 1) Conexão com o banco
try {
    $pdo->query("SELECT 1");
} catch (Throwable $e) {
    $errors[] = "Falha na conexão com o banco: " . $e->getMessage();
}

// 1.1) Verifica se as tabelas base existem
$requiredTables = ['users', 'tools', 'loans', 'loan_items', 'maintenance'];
try {
    foreach ($requiredTables as $table) {
        $exists = $pdo->query("SHOW TABLES LIKE '{$table}'")->fetchColumn();
        if (!$exists) {
            $errors[] = "Tabela obrigatória ausente: {$table}. Importe bd/banco_zerado.sql.";
        }
    }
} catch (Throwable $e) {
    $errors[] = "Erro ao verificar tabelas: " . $e->getMessage();
}

// 2) Verifica colunas de soft delete
try {
    $hasToolDeleted = $pdo->query("SHOW COLUMNS FROM tools LIKE 'deleted_at'")->fetchColumn();
    $hasUserDeleted = $pdo->query("SHOW COLUMNS FROM users LIKE 'deleted_at'")->fetchColumn();
    if (!$hasToolDeleted || !$hasUserDeleted) {
        $errors[] = "Colunas deleted_at não encontradas em tools/users.";
    }
} catch (Throwable $e) {
    $errors[] = "Erro ao validar colunas de soft delete: " . $e->getMessage();
}

// 3) CSRF token
$token = csrf_token();
if (!$token || strlen($token) < 10) {
    $errors[] = "Token CSRF não gerado corretamente.";
}

if ($errors) {
    echo "SMOKE TEST: FALHOU\n";
    foreach ($errors as $err) {
        echo "- $err\n";
    }
    exit(1);
}

echo "SMOKE TEST: OK\n";
exit(0);
