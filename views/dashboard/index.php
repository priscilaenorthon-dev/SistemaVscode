<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
        <p class="text-secondary mb-0">Visão geral do sistema e indicadores</p>
    </div>
    <div>
        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
            <i class="bi bi-calendar-event me-2"></i> <?php echo date('d/m/Y'); ?>
        </span>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <a href="<?php echo BASE_URL; ?>/?route=tools" class="card h-100 border-0 shadow-sm-custom text-decoration-none card-hover-effect">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary me-3">
                        <i class="bi bi-tools fs-4"></i>
                    </div>
                    <h6 class="card-subtitle text-muted text-uppercase small fw-bold">Total Ferramentas</h6>
                </div>
                <h2 class="display-5 fw-bold mb-0 text-dark"><?php echo $stats['total_tools']; ?></h2>
                <small class="text-muted">Cadastradas no sistema</small>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo BASE_URL; ?>/?route=tools&status=available" class="card h-100 border-0 shadow-sm-custom text-decoration-none card-hover-effect">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success me-3">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <h6 class="card-subtitle text-muted text-uppercase small fw-bold">Disponíveis</h6>
                </div>
                <h2 class="display-5 fw-bold mb-0 text-dark"><?php echo $stats['available_tools']; ?></h2>
                <small class="text-success"><i class="bi bi-graph-up"></i> Prontas para uso</small>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo BASE_URL; ?>/?route=tools&status=borrowed" class="card h-100 border-0 shadow-sm-custom text-decoration-none card-hover-effect">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning me-3">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <h6 class="card-subtitle text-muted text-uppercase small fw-bold">Emprestadas</h6>
                </div>
                <h2 class="display-5 fw-bold mb-0 text-dark"><?php echo $stats['borrowed_tools']; ?></h2>
                <small class="text-warning">Em uso atualmente</small>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo BASE_URL; ?>/?route=tools&status=maintenance" class="card h-100 border-0 shadow-sm-custom text-decoration-none card-hover-effect">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger bg-opacity-10 p-3 rounded-3 text-danger me-3">
                        <i class="bi bi-wrench-adjustable fs-4"></i>
                    </div>
                    <h6 class="card-subtitle text-muted text-uppercase small fw-bold">Manutenção</h6>
                </div>
                <h2 class="display-5 fw-bold mb-0 text-dark"><?php echo $stats['maintenance_tools']; ?></h2>
                <small class="text-danger">Indisponíveis</small>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-lightning-charge me-2 text-warning"></i>Ações Rápidas</h5>
            </div>
            <div class="card-body">
                <?php if ($_SESSION['user_level'] == 'admin' || $_SESSION['user_level'] == 'operator'): ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="<?php echo BASE_URL; ?>/?route=loans_create" class="btn btn-primary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                                <i class="bi bi-arrow-left-right fs-3"></i>
                                <span>Registrar Empréstimo</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo BASE_URL; ?>/?route=loans" class="btn btn-outline-secondary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                                <i class="bi bi-list-check fs-3"></i>
                                <span>Gerenciar Devoluções</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo BASE_URL; ?>/?route=tools_create" class="btn btn-outline-success w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                                <i class="bi bi-plus-circle fs-3"></i>
                                <span>Cadastrar Ferramenta</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo BASE_URL; ?>/?route=tools" class="btn btn-outline-primary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                                <i class="bi bi-search fs-3"></i>
                                <span>Consultar Acervo</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Dashboard Personalizado para Usuário Comum -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                <i class="bi bi-clipboard-check fs-3 text-primary d-block mb-2"></i>
                                <h3 class="fw-bold mb-0"><?php echo $userStats['total_loans']; ?></h3>
                                <small class="text-muted">Total de Empréstimos</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                <i class="bi bi-hourglass-split fs-3 text-warning d-block mb-2"></i>
                                <h3 class="fw-bold mb-0"><?php echo $userStats['active_loans']; ?></h3>
                                <small class="text-muted">Em Aberto</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                <i class="bi bi-tools fs-3 text-success d-block mb-2"></i>
                                <h3 class="fw-bold mb-0"><?php echo $userStats['tools_used']; ?></h3>
                                <small class="text-muted">Ferramentas Usadas</small>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Meus Últimos Empréstimos</h6>
                    <?php if (empty($userLoans)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            <p>Você ainda não possui empréstimos registrados</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($userLoans as $loan): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-medium"><?php echo date('d/m/Y', strtotime($loan['loan_date'])); ?></div>
                                            <small class="text-muted"><?php echo $loan['items_count']; ?> ferramenta(s) | Operador: <?php echo htmlspecialchars($loan['operator_name']); ?></small>
                                        </div>
                                        <span class="badge <?php echo $loan['status'] == 'open' ? 'bg-warning' : 'bg-success'; ?> rounded-pill">
                                            <?php echo $loan['status'] == 'open' ? 'Em aberto' : 'Devolvido'; ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-activity me-2 text-info"></i>Status do Sistema</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                <i class="bi bi-people text-primary"></i>
                            </div>
                            <span>Usuários Ativos</span>
                        </div>
                        <span class="badge bg-primary rounded-pill"><?php echo $stats['active_users']; ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                <i class="bi bi-database text-success"></i>
                            </div>
                            <span>Banco de Dados</span>
                        </div>
                        <span class="badge bg-success rounded-pill">Online</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="bi bi-server text-info"></i>
                            </div>
                            <span>Versão</span>
                        </div>
                        <span class="text-muted small">v1.0.0</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
