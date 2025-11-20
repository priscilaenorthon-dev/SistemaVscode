<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Relatórios e Estatísticas</h2>
        <p class="text-secondary mb-0">Análise de uso e desempenho do sistema</p>
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="bi bi-printer me-2"></i>Imprimir
        </button>
    </div>
</div>

<!-- Filtro de Período -->
<div class="card border-0 shadow-sm-custom mb-4">
    <div class="card-body p-4">
        <form action="<?php echo BASE_URL; ?>/?route=reports" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="route" value="reports">
            <div class="col-md-4">
                <label class="form-label fw-medium text-secondary small text-uppercase">Data Inicial</label>
                <input type="date" class="form-control" name="start_date" value="<?php echo $start_date; ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium text-secondary small text-uppercase">Data Final</label>
                <input type="date" class="form-control" name="end_date" value="<?php echo $end_date; ?>" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-graph-up me-2"></i>Gerar Relatório
                </button>
            </div>
        </form>
    </div>
</div>

<!-- KPIs do Período -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm-custom h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary me-3">
                        <i class="bi bi-clipboard-check fs-4"></i>
                    </div>
                    <h6 class="card-subtitle text-muted text-uppercase small fw-bold mb-0">Total Empréstimos</h6>
                </div>
                <h2 class="display-5 fw-bold mb-0 text-dark"><?php echo $stats['total_loans']; ?></h2>
                <small class="text-muted">No período selecionado</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm-custom h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning me-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <h6 class="card-subtitle text-muted text-uppercase small fw-bold mb-0">Em Aberto</h6>
                </div>
                <h2 class="display-5 fw-bold mb-0 text-dark"><?php echo $stats['open_loans']; ?></h2>
                <small class="text-warning">Aguardando devolução</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm-custom h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger bg-opacity-10 p-3 rounded-3 text-danger me-3">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                    <h6 class="card-subtitle text-muted text-uppercase small fw-bold mb-0">Abertos +7 dias</h6>
                </div>
                <h2 class="display-5 fw-bold mb-0 text-dark"><?php echo $stats['overdue_loans']; ?></h2>
                <small class="text-danger">Empréstimos antigos</small>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos e Tabelas -->
<div class="row g-4 mb-4">
    <!-- Top 10 Ferramentas -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm-custom h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-trophy text-warning me-2"></i>Ferramentas Mais Emprestadas
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th class="text-end pe-4">Empréstimos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($topTools)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Nenhum dado no período
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $rank = 1; foreach ($topTools as $tool): ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge <?php echo $rank <= 3 ? 'bg-warning text-dark' : 'bg-light text-dark'; ?> rounded-circle">
                                            <?php echo $rank++; ?>
                                        </span>
                                    </td>
                                    <td><span class="font-monospace fw-bold"><?php echo htmlspecialchars($tool['code']); ?></span></td>
                                    <td><?php echo htmlspecialchars($tool['description']); ?></td>
                                    <td class="text-end pe-4">
                                        <span class="badge bg-primary rounded-pill"><?php echo $tool['loan_count']; ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 Usuários -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm-custom h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-people text-primary me-2"></i>Usuários Mais Ativos
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Nome</th>
                                <th>Setor</th>
                                <th class="text-end pe-4">Empréstimos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($topUsers)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Nenhum dado no período
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $rank = 1; foreach ($topUsers as $user): ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge <?php echo $rank <= 3 ? 'bg-success text-white' : 'bg-light text-dark'; ?> rounded-circle">
                                            <?php echo $rank++; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-medium"><?php echo htmlspecialchars($user['name']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($user['registration']); ?></small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($user['sector']); ?></span></td>
                                    <td class="text-end pe-4">
                                        <span class="badge bg-success rounded-pill"><?php echo $user['loan_count']; ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Distribuição por Status -->
<div class="row g-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm-custom">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-pie-chart text-success me-2"></i>Distribuição de Empréstimos por Status
                </h5>
            </div>
            <div class="card-body p-4">
                <?php if (empty($loansByStatus)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Nenhum empréstimo no período selecionado
                    </div>
                <?php else: ?>
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <canvas id="statusChart" height="100"></canvas>
                        </div>
                        <div class="col-md-4">
                            <div class="list-group list-group-flush">
                                <?php foreach ($loansByStatus as $item): ?>
                                    <div class="list-group-item border-0 d-flex justify-content-between align-items-center px-0">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="bi bi-circle-fill <?php echo $item['status'] == 'open' ? 'text-warning' : 'text-success'; ?>"></i>
                                            </div>
                                            <span class="fw-medium"><?php echo $item['status'] == 'open' ? 'Abertos' : 'Fechados'; ?></span>
                                        </div>
                                        <span class="badge bg-light text-dark border fs-6"><?php echo $item['count']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($loansByStatus)): ?>
    const ctx = document.getElementById('statusChart');
    const data = {
        labels: [<?php foreach ($loansByStatus as $item): ?>'<?php echo $item['status'] == 'open' ? 'Abertos' : 'Fechados'; ?>',<?php endforeach; ?>],
        datasets: [{
            data: [<?php foreach ($loansByStatus as $item): ?><?php echo $item['count']; ?>,<?php endforeach; ?>],
            backgroundColor: ['#fbbf24', '#10b981'],
            borderWidth: 0
        }]
    };

    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    <?php endif; ?>
});
</script>

<style>
@media print {
    .btn, form, .navbar, .footer {
        display: none !important;
    }
}
</style>
