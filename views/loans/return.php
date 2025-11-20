<div class="row mb-3">
    <div class="col-md-12">
        <h2>Registrar Devolução</h2>
        <p class="text-muted">Empréstimo #<?php echo $loan['id']; ?> - <?php echo htmlspecialchars($loan['user_name']); ?></p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>/?route=loans_confirm_return" method="POST">
            <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>">
            
            <div class="table-responsive mb-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ferramenta</th>
                            <th>Status Atual</th>
                            <th>Condição de Devolução</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <?php if ($item['status'] == 'borrowed'): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($item['code']); ?></strong><br>
                                    <?php echo htmlspecialchars($item['description']); ?>
                                </td>
                                <td><span class="badge bg-warning">Emprestado</span></td>
                                <td>
                                    <input type="text" class="form-control" name="items[<?php echo $item['id']; ?>]" placeholder="Ex: Bom estado, Danificado..." value="Bom estado">
                                </td>
                                <td>
                                    <span class="text-success"><i class="bi bi-check-circle"></i> Devolver</span>
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr class="table-secondary">
                                <td>
                                    <strong><?php echo htmlspecialchars($item['code']); ?></strong><br>
                                    <?php echo htmlspecialchars($item['description']); ?>
                                </td>
                                <td><span class="badge bg-success">Devolvido</span></td>
                                <td><?php echo htmlspecialchars($item['return_condition']); ?></td>
                                <td>-</td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?php echo BASE_URL; ?>/?route=loans" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-success">Confirmar Devolução</button>
            </div>
        </form>
    </div>
</div>
