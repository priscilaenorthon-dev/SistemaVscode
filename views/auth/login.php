<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Ferramentaria</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border-radius: 1rem;
            background: var(--surface-color);
            box-shadow: var(--card-hover-shadow);
            border: 1px solid var(--border-color);
        }
        .brand-icon {
            width: 48px;
            height: 48px;
            background: var(--primary-color);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 1.5rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand-icon">
            <i class="bi bi-tools"></i>
        </div>
        <h1 class="h3 fw-bold mb-1">Bem-vindo de volta</h1>
        <p class="text-secondary">Acesse o sistema de ferramentaria</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <div><?php echo $error; ?></div>
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>/?route=auth_login" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label fw-medium">Email</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-secondary"></i></span>
                <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" required placeholder="seu@email.com">
            </div>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label fw-medium">Senha</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-secondary"></i></span>
                <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" required placeholder="••••••">
            </div>
        </div>
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary py-2">
                Entrar no Sistema
            </button>
        </div>
        <div class="text-center">
            <a href="#" class="text-decoration-none small text-secondary hover-primary">Esqueceu sua senha?</a>
        </div>
    </form>
    
    <div class="mt-4 pt-3 border-top text-center">
        <p class="small text-secondary mb-0">
            Credenciais de Teste: <span class="fw-medium text-dark">admin@empresa.com</span> / <span class="fw-medium text-dark">admin123</span>
        </p>
    </div>
</div>

</body>
</html>
