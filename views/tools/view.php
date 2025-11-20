<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Detalhes da Ferramenta</h2>
        <p class="text-secondary mb-0">Informações completas e histórico de uso</p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>/?route=tools" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-2"></i>Voltar
        </a>
        <a href="<?php echo BASE_URL; ?>/?route=tools_edit&id=<?php echo $tool['id']; ?>" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Editar
        </a>
    </div>
</div>

<!-- Informações da Ferramenta -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm-custom h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-info-circle text-primary me-2"></i>Informações Gerais
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="text-secondary small text-uppercase fw-bold mb-2">Código</label>
                            <div class="fs-4 font-monospace fw-bold text-dark"><?php echo htmlspecialchars($tool['code']); ?></div>
                        </div>
                        <div class="mb-4">
                            <label class="text-secondary small text-uppercase fw-bold mb-2">Descrição</label>
                            <div class="fs-5 text-dark"><?php echo htmlspecialchars($tool['description']); ?></div>
                        </div>
                        <div class="mb-4">
                            <label class="text-secondary small text-uppercase fw-bold mb-2">Categoria</label>
                            <div><span class="badge bg-light text-dark border fs-6"><?php echo htmlspecialchars($tool['category_name']); ?></span></div>
                        </div>
                        <div>
                            <label class="text-secondary small text-uppercase fw-bold mb-2">Modelo</label>
                            <div class="text-dark"><?php echo htmlspecialchars($tool['model_name']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="text-secondary small text-uppercase fw-bold mb-2">Fabricante</label>
                            <div class="text-dark"><?php echo htmlspecialchars($tool['manufacturer']); ?></div>
                        </div>
                        <div class="mb-4">
                            <label class="text-secondary small text-uppercase fw-bold mb-2">Número de Série</label>
                            <div class="font-monospace text-dark"><?php echo htmlspecialchars($tool['serial_number']); ?></div>
                        </div>
                        <div class="mb-4">
                            <label class="text-secondary small text-uppercase fw-bold mb-2">Localização</label>
                            <div class="text-dark"><i class="bi bi-geo-alt text-secondary me-1"></i><?php echo htmlspecialchars($tool['location']); ?></div>
                        </div>
                        <div>
                            <label class="text-secondary small text-uppercase fw-bold mb-2">Data de Aquisição</label>
                            <div class="text-dark"><?php echo $tool['acquisition_date'] ? date('d/m/Y', strtotime($tool['acquisition_date'])) : 'Não informada'; ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-top">
                    <label class="text-secondary small text-uppercase fw-bold mb-2">Status Atual</label>
                    <div>
                        <?php 
                            $statusClass = 'secondary';
                            $statusIcon = 'circle';
                            $statusLabel = $tool['status'];
                            switch($tool['status']) {
                                case 'available': 
                                    $statusClass = 'success'; 
                                    $statusIcon = 'check-circle';
                                    $statusLabel = 'Disponível'; 
                                    break;
                                case 'borrowed': 
                                    $statusClass = 'warning'; 
                                    $statusIcon = 'clock';
                                    $statusLabel = 'Emprestada'; 
                                    break;
                                case 'maintenance': 
                                    $statusClass = 'danger'; 
                                    $statusIcon = 'wrench';
                                    $statusLabel = 'Manutenção'; 
                                    break;
                                case 'inactive':
                                    $statusClass = 'secondary';
                                    $statusIcon = 'x-circle';
                                    $statusLabel = 'Inativa';
                                    break;
                            }
                        ?>
                        <span class="badge bg-<?php echo $statusClass; ?> bg-opacity-10 text-<?php echo $statusClass; ?> border border-<?php echo $statusClass; ?> border-opacity-10 rounded-pill px-4 py-2 fs-6">
                            <i class="bi bi-<?php echo $statusIcon; ?> me-2"></i><?php echo $statusLabel; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm-custom mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-graph-up text-success me-2"></i>Estatísticas
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                            <i class="bi bi-clipboard-check text-primary"></i>
                        </div>
                        <div>
                            <div class="text-secondary small">Total de Empréstimos</div>
                            <div class="fs-4 fw-bold text-dark"><?php echo $stats['total_loans']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                            <i class="bi bi-hourglass-split text-warning"></i>
                        </div>
                        <div>
                            <div class="text-secondary small">Empréstimos Ativos</div>
                            <div class="fs-4 fw-bold text-dark"><?php echo $stats['active_loans']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Histórico de Empréstimos -->
<div class="card border-0 shadow-sm-custom">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="card-title mb-0 fw-bold">
            <i class="bi bi-clock-history text-secondary me-2"></i>Histórico de Empréstimos
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Data Empréstimo</th>
                        <th>Usuário</th>
                        <th>Setor</th>
                        <th>Operador</th>
                        <th>Quantidade</th>
                        <th>Data Devolução</th>
                        <th>Condição</th>
                        <th class="text-end pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($loanHistory)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Nenhum empréstimo registrado para esta ferramenta
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($loanHistory as $history): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-medium"><?php echo date('d/m/Y', strtotime($history['loan_date'])); ?></div>
                                <small class="text-muted"><?php echo date('H:i', strtotime($history['loan_date'])); ?></small>
                            </td>
                            <td>
                                <div class="fw-medium"><?php echo htmlspecialchars($history['user_name']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($history['registration']); ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($history['sector']); ?></span></td>
                            <td><small class="text-muted"><?php echo htmlspecialchars($history['operator_name']); ?></small></td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                    <i class="bi bi-box-seam me-1"></i><?php echo $history['quantity']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($history['return_date']): ?>
                                    <div><?php echo date('d/m/Y', strtotime($history['return_date'])); ?></div>
                                    <small class="text-muted"><?php echo date('H:i', strtotime($history['return_date'])); ?></small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($history['return_condition']): ?>
                                    <small class="text-muted"><?php echo htmlspecialchars($history['return_condition']); ?></small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <?php if ($history['item_status'] == 'borrowed'): ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-10 rounded-pill">
                                        <i class="bi bi-clock me-1"></i>Em uso
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i>Devolvida
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
