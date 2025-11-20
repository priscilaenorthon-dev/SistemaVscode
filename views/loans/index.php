<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Empréstimos</h2>
        <p class="text-secondary mb-0">Gerencie as saídas e devoluções</p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>/?route=loans_create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Novo Empréstimo
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm-custom mb-4">
    <div class="card-body p-2">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link <?php echo (!isset($_GET['status']) || $_GET['status'] == 'open') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/?route=loans&status=open">
                    <i class="bi bi-hourglass-split me-2"></i>Em Aberto
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (isset($_GET['status']) && $_GET['status'] == 'closed') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/?route=loans&status=closed">
                    <i class="bi bi-archive me-2"></i>Histórico (Fechados)
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="card border-0 shadow-sm-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Usuário</th>
                        <th>Data Empréstimo</th>
                        <th>Qtd Itens</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($loans) > 0): ?>
                        <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td class="ps-4"><span class="font-monospace text-muted">#<?php echo $loan['id']; ?></span></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($loan['user_name']); ?></div>
                                <small class="text-muted"><i class="bi bi-person-badge me-1"></i>Op: <?php echo htmlspecialchars($loan['operator_name']); ?></small>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($loan['loan_date'])); ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo $loan['total_items']; ?> itens</span></td>
                            <td>
                                <span class="badge bg-<?php echo $loan['status'] == 'open' ? 'warning' : 'success'; ?> bg-opacity-10 text-<?php echo $loan['status'] == 'open' ? 'warning' : 'success'; ?> border border-<?php echo $loan['status'] == 'open' ? 'warning' : 'success'; ?> border-opacity-10 rounded-pill px-3">
                                    <?php echo $loan['status'] == 'open' ? 'Em Aberto' : 'Finalizado'; ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <?php if ($loan['status'] == 'open'): ?>
                                        <a href="<?php echo BASE_URL; ?>/?route=loans_return&id=<?php echo $loan['id']; ?>" class="btn btn-sm btn-success" title="Devolver">
                                            <i class="bi bi-box-arrow-in-down"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-light text-muted" disabled><i class="bi bi-check2-all"></i></button>
                                    <?php endif; ?>
                                    <a href="<?php echo BASE_URL; ?>/?route=loans_print&id=<?php echo $loan['id']; ?>" target="_blank" class="btn btn-sm btn-light text-dark border" title="Imprimir Termo"><i class="bi bi-printer"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x fs-1 d-block mb-2"></i>
                                Nenhum registro encontrado.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
