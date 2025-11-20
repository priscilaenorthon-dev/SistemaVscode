<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferramentaria</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>/?route=dashboard">
        <i class="bi bi-tools"></i> Ferramentaria
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo BASE_URL; ?>/?route=dashboard">Dashboard</a>
        </li>
        <?php if ($_SESSION['user_level'] == 'admin' || $_SESSION['user_level'] == 'operator'): ?>
            <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>/?route=loans">Empréstimos</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>/?route=tools">Ferramentas</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>/?route=reports">Relatórios</a>
            </li>
        <?php endif; ?>
        <?php if ($_SESSION['user_level'] == 'admin'): ?>
            <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>/?route=users">Usuários</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>/?route=audit_logs">Auditoria</a>
            </li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center text-white">
        <div class="theme-toggle me-3" id="themeToggle" title="Alternar tema"></div>
        <span class="me-3">Olá, <?php echo $_SESSION['user_name']; ?></span>
        <a href="<?php echo BASE_URL; ?>/?route=logout" class="btn btn-outline-light btn-sm">Sair</a>
      </div>
    </div>
  </div>
</nav>

<script>
const themeToggle = document.getElementById('themeToggle');
const htmlElement = document.documentElement;
const currentTheme = localStorage.getItem('theme') || 'light';
htmlElement.setAttribute('data-theme', currentTheme);

themeToggle.addEventListener('click', function() {
    const theme = htmlElement.getAttribute('data-theme');
    const newTheme = theme === 'light' ? 'dark' : 'light';    
    htmlElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
});
</script>

<div class="container mt-4">
