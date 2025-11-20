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
        :root {
            --bg: #0b1020;
            --card: rgba(255,255,255,0.92);
            --gradient: radial-gradient(circle at 10% 20%, #8b5cf6, transparent 25%), radial-gradient(circle at 80% 0%, #06b6d4, transparent 20%), radial-gradient(circle at 60% 85%, #22c55e, transparent 25%);
        }
        body {
            background: var(--bg);
            background-image: var(--gradient);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 40px;
            font-family: 'Inter', sans-serif;
        }
        .login-shell {
            width: 100%;
            max-width: 1040px;
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 24px;
            align-items: center;
        }
        .hero-card {
            color: #e2e8f0;
            padding: 32px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(12,18,35,0.8), rgba(12,18,35,0.55));
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 25px 70px rgba(0,0,0,0.35);
            position: relative;
            overflow: hidden;
        }
        .hero-card::after {
            content: "";
            position: absolute;
            inset: -40% 10% auto auto;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.35), transparent 55%);
            filter: blur(10px);
        }
        .hero-card h1 {
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 12px;
        }
        .hero-card p {
            color: #cbd5e1;
            margin-bottom: 16px;
        }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 12px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            margin-right: 10px;
            color: #e2e8f0;
        }
        .login-card {
            background: var(--card);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: 0 25px 70px rgba(0,0,0,0.25);
            border-radius: 18px;
            padding: 2.75rem;
        }
        .brand-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto 1.5rem;
            box-shadow: 0 12px 30px rgba(99,102,241,0.35);
        }
        .input-group .form-control {
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border: none;
            box-shadow: 0 10px 30px rgba(99,102,241,0.35);
        }
        .cred-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #e2e8f0;
        }
        @media (max-width: 980px) {
            .login-shell { grid-template-columns: 1fr; }
            .hero-card { order: 2; }
            .login-card { order: 1; }
        }
    </style>
</head>
<body>
<div class="login-shell">
    <div class="hero-card">
        <h1>Controle total do seu acervo</h1>
        <p>Entre para registrar, acompanhar e devolver ferramentas com segurança e rapidez.</p>
        <div class="mb-3">
            <span class="pill"><i class="bi bi-shield-check"></i> Segurança</span>
            <span class="pill"><i class="bi bi-lightning-charge"></i> Agilidade</span>
            <span class="pill"><i class="bi bi-eye"></i> Transparência</span>
        </div>
        <ul class="mb-0 ps-3">
            <li>Visualize empréstimos e devoluções em tempo real.</li>
            <li>Controle de estoque e manutenção sem complicações.</li>
            <li>Perfis de acesso separados para cada papel.</li>
        </ul>
    </div>

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
            <?php echo csrf_field(); ?>
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
                    <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" required placeholder="••••••••">
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
        
        <div class="mt-4 pt-3 border-top">
            <p class="small text-secondary mb-2 fw-bold text-center">Credenciais de Teste</p>
            <div class="cred-section small">
                Admin: <span class="fw-medium text-dark">admin@empresa.com</span> / <span class="fw-medium text-dark">password</span><br>
                Operador: <span class="fw-medium text-dark">operador@empresa.com</span> / <span class="fw-medium text-dark">password</span><br>
                Usuário: <span class="fw-medium text-dark">usuario@usuario.com.br</span> / <span class="fw-medium text-dark">password</span>
            </div>
        </div>
    </div>
</div>
</body>
</html>
