<div class="row mb-3">
    <div class="col-md-12">
        <h2>Manutenção e Calibração</h2>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Pendências
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Ferramenta</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Data Agendada</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($maintenances) > 0): ?>
                        <?php foreach ($maintenances as $m): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($m['code']); ?></strong><br>
                                <?php echo htmlspecialchars($m['description']); ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $m['type'] == 'calibration' ? 'info' : 'warning'; ?>">
                                    <?php echo ucfirst($m['type']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($m['description']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($m['scheduled_date'])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-success" title="Concluir"><i class="bi bi-check"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Nenhuma manutenção pendente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
