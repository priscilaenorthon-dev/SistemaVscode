<div class="row mb-3">
    <div class="col-md-6">
        <h2>Gestão de Usuários</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>/?route=users_create" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Novo Usuário
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Nível</th>
                        <th>Setor</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $user['level'] == 'admin' ? 'danger' : ($user['level'] == 'operator' ? 'warning' : 'info'); ?>">
                                <?php echo ucfirst($user['level']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($user['sector']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $user['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                <?php echo $user['status'] == 'active' ? 'Ativo' : 'Inativo'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/?route=users_edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <!-- Botão QR Code Simulado -->
                            <button type="button" class="btn btn-sm btn-outline-dark" onclick="alert('QR Code para ID: <?php echo $user['id']; ?>')">
                                <i class="bi bi-qr-code"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
