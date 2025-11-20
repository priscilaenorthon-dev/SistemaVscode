<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Soft Delete - Sistema Ferramentaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="bi bi-database-add"></i> Adicionar Soft Delete ao Banco</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="bi bi-info-circle"></i> O que é Soft Delete?</h5>
                    <p>Ao invés de deletar permanentemente, apenas marcamos o registro como deletado.</p>
                    <p><strong>Vantagens:</strong></p>
                    <ul>
                        <li>Possibilidade de recuperar dados deletados</li>
                        <li>Manter histórico completo</li>
                        <li>Auditoria e rastreabilidade</li>
                    </ul>
                </div>
                
                <pre class="bg-dark text-light p-3 rounded" style="max-height: 500px; overflow-y: auto;">
<?php
require_once 'config/database.php';

echo "=== ADICIONANDO SOFT DELETE ===\n\n";

try {
    $pdo->beginTransaction();
    
    // Adicionar campo deleted_at na tabela tools
    echo "1. Adicionando 'deleted_at' na tabela 'tools'...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM tools LIKE 'deleted_at'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE tools ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER created_at");
        echo "   ✓ Campo 'deleted_at' adicionado em 'tools'\n";
    } else {
        echo "   ℹ Campo 'deleted_at' já existe em 'tools'\n";
    }
    echo "\n";
    
    // Adicionar campo deleted_at na tabela users
    echo "2. Adicionando 'deleted_at' na tabela 'users'...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'deleted_at'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER created_at");
        echo "   ✓ Campo 'deleted_at' adicionado em 'users'\n";
    } else {
        echo "   ℹ Campo 'deleted_at' já existe em 'users'\n";
    }
    echo "\n";
    
    // Criar índices para performance
    echo "3. Criando índices para melhor performance...\n";
    try {
        $pdo->exec("CREATE INDEX idx_tools_deleted ON tools(deleted_at)");
        echo "   ✓ Índice criado em 'tools.deleted_at'\n";
    } catch (PDOException $e) {
        echo "   ℹ Índice já existe em 'tools.deleted_at'\n";
    }
    
    try {
        $pdo->exec("CREATE INDEX idx_users_deleted ON users(deleted_at)");
        echo "   ✓ Índice criado em 'users.deleted_at'\n";
    } catch (PDOException $e) {
        echo "   ℹ Índice já existe em 'users.deleted_at'\n";
    }
    echo "\n";
    
    $pdo->commit();
    
    echo "===========================================\n";
    echo "✓ SOFT DELETE CONFIGURADO COM SUCESSO!\n";
    echo "===========================================\n\n";
    echo "Agora o sistema pode:\n";
    echo "- Marcar ferramentas como deletadas\n";
    echo "- Marcar usuários como deletados\n";
    echo "- Recuperar registros deletados se necessário\n";
    echo "- Manter histórico completo\n\n";
    echo "Você pode fechar esta página e usar o sistema!\n";
    
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "\n❌ ERRO: " . $e->getMessage() . "\n";
}
?>
                </pre>
                
                <div class="mt-3">
                    <a href="public/index.php" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Ir para o Sistema
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
