<div class="row mb-3">
    <div class="col-md-6">
        <h2>Gestão de Usuários</h2>
    </div>
    <div class="col-md-6 text-end">
        <form class="d-inline me-2" method="GET" action="<?php echo BASE_URL; ?>/">
            <input type="hidden" name="route" value="users">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="include_deleted_users" name="include_deleted" value="1" <?php echo isset($_GET['include_deleted']) && $_GET['include_deleted'] == '1' ? 'checked' : ''; ?>>
                <label class="form-check-label small" for="include_deleted_users">Incluir arquivados</label>
            </div>
            <button type="submit" class="btn btn-outline-secondary btn-sm">Aplicar</button>
        </form>
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
                            <?php if (!empty($user['deleted_at'])): ?>
                                <span class="badge bg-secondary">Arquivado</span>
                            <?php else: ?>
                                <span class="badge bg-<?php echo $user['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo $user['status'] == 'active' ? 'Ativo' : 'Inativo'; ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/?route=users_edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if (empty($user['deleted_at'])): ?>
                                <form action="<?php echo BASE_URL; ?>/?route=users_delete" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Arquivar este usuário?');">
                                        <i class="bi bi-archive"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="<?php echo BASE_URL; ?>/?route=users_restore" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
