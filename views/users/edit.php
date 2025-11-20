<div class="row mb-3">
    <div class="col-md-12">
        <h2>Editar Usuário</h2>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
<form action="<?php echo BASE_URL; ?>/?route=users_update" method="POST">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email (Login)</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="password" class="form-label">Nova Senha (deixe em branco para manter)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="col-md-4">
                    <label for="level" class="form-label">Nível de Acesso</label>
                    <select class="form-select" id="level" name="level" required>
                        <option value="user" <?php echo $user['level'] == 'user' ? 'selected' : ''; ?>>Usuário</option>
                        <option value="operator" <?php echo $user['level'] == 'operator' ? 'selected' : ''; ?>>Operador</option>
                        <option value="admin" <?php echo $user['level'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="registration" class="form-label">Matrícula</label>
                    <input type="text" class="form-control" id="registration" name="registration" value="<?php echo htmlspecialchars($user['registration']); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="sector" class="form-label">Setor</label>
                    <input type="text" class="form-control" id="sector" name="sector" value="<?php echo htmlspecialchars($user['sector']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" <?php echo $user['status'] == 'active' ? 'selected' : ''; ?>>Ativo</option>
                        <option value="inactive" <?php echo $user['status'] == 'inactive' ? 'selected' : ''; ?>>Inativo</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?php echo BASE_URL; ?>/?route=users" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>
