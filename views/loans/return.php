<div class="row mb-3">
    <div class="col-md-12">
        <h2>Registrar Devolução</h2>
        <p class="text-muted">Empréstimo #<?php echo $loan['id']; ?> - <?php echo htmlspecialchars($loan['user_name']); ?></p>
    </div>
</div>

<div class="alert alert-info mb-4">
    <h6 class="mb-1"><i class="bi bi-info-circle me-2"></i>Como registrar devoluções parciais e danificadas</h6>
    <ul class="mb-0">
        <li>Em <strong>Condição de Devolução</strong>, selecione “Bom estado” ou “Danificado”. Se “Danificado”, o sistema abre manutenção automática para a ferramenta.</li>
        <li>Em <strong>Qtd. a devolver</strong>, informe quanto está voltando agora. Pode devolver menos do que pegou; o restante segue emprestado.</li>
        <li>Devolução em bom estado soma ao disponível. Danificado reduz disponível/total e gera uma ordem em <strong>Manutenção</strong> no menu (controle > Manutenção).</li>
        <li>O empréstimo só fecha quando todos os itens estiverem totalmente devolvidos.</li>
    </ul>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>/?route=loans_confirm_return" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>">
            
            <div class="table-responsive mb-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ferramenta</th>
                            <th>Status Atual</th>
                            <th>Condição de Devolução</th>
                            <th>Qtd. a devolver</th>
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
                                <td style="max-width: 200px;">
                                    <select class="form-select" name="items[<?php echo $item['id']; ?>][condition]">
                                        <option value="Bom estado">Bom estado</option>
                                        <option value="Danificado">Danificado</option>
                                    </select>
                                </td>
                                <td style="max-width: 140px;">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="items[<?php echo $item['id']; ?>][quantity]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['quantity']; ?>">
                                        <span class="input-group-text">/ <?php echo $item['quantity']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="items[<?php echo $item['id']; ?>][confirm]" value="1" id="return_<?php echo $item['id']; ?>" checked>
                                        <label class="form-check-label" for="return_<?php echo $item['id']; ?>">
                                            Devolver agora
                                        </label>
                                    </div>
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
