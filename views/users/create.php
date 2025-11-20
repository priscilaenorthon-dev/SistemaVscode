<div class="row mb-3">
    <div class="col-md-12">
        <h2>Novo Usuário</h2>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>/?route=users_store" method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email (Login)</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-md-4">
                    <label for="level" class="form-label">Nível de Acesso</label>
                    <select class="form-select" id="level" name="level" required>
                        <option value="user">Usuário</option>
                        <option value="operator">Operador</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="registration" class="form-label">Matrícula</label>
                    <input type="text" class="form-control" id="registration" name="registration">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="sector" class="form-label">Setor</label>
                    <input type="text" class="form-control" id="sector" name="sector">
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?php echo BASE_URL; ?>/?route=users" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>
