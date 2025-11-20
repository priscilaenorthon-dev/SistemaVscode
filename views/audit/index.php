<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Auditoria</h2>
        <p class="text-secondary mb-0">Entenda rapidamente quem fez o quê e quando.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo BASE_URL; ?>/?route=audit_logs" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-clockwise me-1"></i>Atualizar
        </a>
        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#ajudaAuditoria">
            <i class="bi bi-question-circle me-1"></i>Ajuda
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm-custom mb-3">
    <div class="card-body">
        <form class="row g-3 align-items-end" method="GET" action="<?php echo BASE_URL; ?>/">
            <input type="hidden" name="route" value="audit_logs">
            <div class="col-md-5">
                <label class="form-label small text-secondary text-uppercase fw-bold">Buscar</label>
                <input type="text" name="q" class="form-control" placeholder="Ex.: criou ferramenta, apagou usuário, devolveu item" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-secondary text-uppercase fw-bold">Tipo de registro</label>
                <input type="text" name="entity" class="form-control" placeholder="Ex.: ferramenta, usuário, empréstimo" value="<?php echo isset($_GET['entity']) ? htmlspecialchars($_GET['entity']) : ''; ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-secondary text-uppercase fw-bold">&nbsp;</label>
                <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search me-1"></i>Filtrar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Ajuda -->
<div class="modal fade" id="ajudaAuditoria" tabindex="-1" aria-labelledby="ajudaAuditoriaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajudaAuditoriaLabel"><i class="bi bi-question-circle me-2"></i>Como usar a Auditoria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <ol class="mb-0">
            <li>Use o campo <strong>Buscar</strong> para digitar o que procura: “criou ferramenta”, “apagou usuário”, “devolveu item”.</li>
            <li>Em <strong>Tipo de registro</strong>, filtre por “ferramenta”, “usuário” ou “empréstimo” se quiser restringir o resultado.</li>
            <li>Leia as colunas:
                <ul>
                    <li><strong>Usuário</strong>: quem fez a ação.</li>
                    <li><strong>Ação</strong>: o que foi feito (ex.: tool_created, user_updated).</li>
                    <li><strong>Registro</strong>: qual item foi afetado (ex.: ferramenta #10).</li>
                    <li><strong>Detalhes</strong>: resumo do que mudou.</li>
                </ul>
            </li>
            <li>Se precisar atualizar a lista, clique em <strong>Atualizar</strong>.</li>
        </ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<div class="card border-0 shadow-sm-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Data</th>
                        <th>Usuário</th>
                        <th>Ação</th>
                        <th>Registro</th>
                        <th>Detalhes (o que mudou)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Nenhum evento registrado ainda.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td class="ps-4"><span class="font-monospace"><?php echo date('d/m/Y H:i', strtotime($log['created_at'])); ?></span></td>
                                <td><?php echo htmlspecialchars($log['user_name'] ?? 'Sistema'); ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($log['action']); ?></span></td>
                                <td><?php echo htmlspecialchars($log['entity']); ?> #<?php echo htmlspecialchars($log['entity_id']); ?></td>
                                <td><span class="small text-wrap text-muted"><?php echo htmlspecialchars($log['details']); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
