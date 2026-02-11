<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <h2><i class="bi bi-wrench-adjustable me-2"></i>Manutenção</h2>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group" role="group">
            <a href="<?php echo BASE_URL; ?>/?route=maintenance&status=pending" class="btn btn-sm <?php echo ($statusFilter ?? 'pending') === 'pending' ? 'btn-warning' : 'btn-outline-warning'; ?>">Pendentes</a>
            <a href="<?php echo BASE_URL; ?>/?route=maintenance&status=in_progress" class="btn btn-sm <?php echo ($statusFilter ?? '') === 'in_progress' ? 'btn-info' : 'btn-outline-info'; ?>">Em Andamento</a>
            <a href="<?php echo BASE_URL; ?>/?route=maintenance&status=completed" class="btn btn-sm <?php echo ($statusFilter ?? '') === 'completed' ? 'btn-success' : 'btn-outline-success'; ?>">Concluídas</a>
            <a href="<?php echo BASE_URL; ?>/?route=maintenance&status=all" class="btn btn-sm <?php echo ($statusFilter ?? '') === 'all' ? 'btn-secondary' : 'btn-outline-secondary'; ?>">Todas</a>
        </div>
    </div>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Registros de Manutenção</span>
        <span class="badge bg-secondary"><?php echo count($maintenances); ?> registro(s)</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Ferramenta</th>
                        <th>Descrição da Manutenção</th>
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Custo</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($maintenances) > 0): ?>
                        <?php foreach ($maintenances as $m): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($m['code']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($m['tool_description'] ?? ''); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($m['description']); ?></td>
                            <td><?php echo $m['start_date'] ? date('d/m/Y', strtotime($m['start_date'])) : '-'; ?></td>
                            <td><?php echo $m['end_date'] ? date('d/m/Y', strtotime($m['end_date'])) : '-'; ?></td>
                            <td><?php echo $m['cost'] ? 'R$ ' . number_format($m['cost'], 2, ',', '.') : '-'; ?></td>
                            <td>
                                <?php
                                    $statusBadge = match($m['status']) {
                                        'pending' => '<span class="badge bg-warning">Pendente</span>',
                                        'in_progress' => '<span class="badge bg-info">Em Andamento</span>',
                                        'completed' => '<span class="badge bg-success">Concluída</span>',
                                        default => '<span class="badge bg-secondary">' . ucfirst($m['status']) . '</span>',
                                    };
                                    echo $statusBadge;
                                ?>
                            </td>
                            <td>
                                <?php if ($m['status'] === 'pending'): ?>
                                    <form action="<?php echo BASE_URL; ?>/?route=maintenance_start" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                                        <button class="btn btn-sm btn-info" title="Iniciar"><i class="bi bi-play-fill"></i></button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($m['status'] === 'pending' || $m['status'] === 'in_progress'): ?>
                                    <form action="<?php echo BASE_URL; ?>/?route=maintenance_complete" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                                        <button class="btn btn-sm btn-success" title="Concluir"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-check-circle fs-4 d-block mb-2"></i>
                                Nenhuma manutenção encontrada para este filtro.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
