<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Editar Ferramenta</h2>
        <p class="text-secondary mb-0">Atualize os dados da ferramenta</p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>/?route=tools" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Voltar
        </a>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <div><?php echo $error; ?></div>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm-custom">
    <div class="card-body p-4">
        <form action="<?php echo BASE_URL; ?>/?route=tools_update" method="POST">
            <input type="hidden" name="id" value="<?php echo $tool['id']; ?>">
            
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Informações Básicas</h5>
                </div>

                <div class="col-md-3">
                    <label for="code" class="form-label fw-medium">Código</label>
                    <input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars($tool['code']); ?>" required>
                </div>
                <div class="col-md-9">
                    <label for="description" class="form-label fw-medium">Descrição</label>
                    <input type="text" class="form-control" id="description" name="description" value="<?php echo htmlspecialchars($tool['description']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="category_id" class="form-label fw-medium">Categoria</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($tool['category_id'] == $cat['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="model_id" class="form-label fw-medium">Modelo</label>
                    <select class="form-select" id="model_id" name="model_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($models as $mod): ?>
                            <option value="<?php echo $mod['id']; ?>" <?php echo ($tool['model_id'] == $mod['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($mod['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Detalhes Técnicos</h5>
                </div>

                <div class="col-md-4">
                    <label for="manufacturer" class="form-label fw-medium">Fabricante</label>
                    <input type="text" class="form-control" id="manufacturer" name="manufacturer" value="<?php echo htmlspecialchars($tool['manufacturer']); ?>">
                </div>
                <div class="col-md-4">
                    <label for="serial_number" class="form-label fw-medium">Número de Série</label>
                    <input type="text" class="form-control" id="serial_number" name="serial_number" value="<?php echo htmlspecialchars($tool['serial_number']); ?>">
                </div>
                <div class="col-md-4">
                    <label for="acquisition_date" class="form-label fw-medium">Data Aquisição</label>
                    <input type="date" class="form-control" id="acquisition_date" name="acquisition_date" value="<?php echo $tool['acquisition_date']; ?>">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Controle de Estoque</h5>
                </div>

                <div class="col-md-4">
                    <label for="quantity" class="form-label fw-medium">Quantidade Total</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="<?php echo $tool['quantity']; ?>" required>
                    <div class="form-text">Total de unidades cadastradas</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Quantidade Disponível</label>
                    <input type="text" class="form-control bg-light" value="<?php echo $tool['available_quantity']; ?>" readonly>
                    <div class="form-text">Calculado automaticamente</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Quantidade Emprestada</label>
                    <input type="text" class="form-control bg-light" value="<?php echo ($tool['quantity'] - $tool['available_quantity']); ?>" readonly>
                    <div class="form-text">Em uso no momento</div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Localização e Status</h5>
                </div>

                <div class="col-md-6">
                    <label for="location" class="form-label fw-medium">Localização</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($tool['location']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label fw-medium">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="available" <?php echo ($tool['status'] == 'available') ? 'selected' : ''; ?>>Disponível</option>
                        <option value="borrowed" <?php echo ($tool['status'] == 'borrowed') ? 'selected' : ''; ?>>Emprestada</option>
                        <option value="maintenance" <?php echo ($tool['status'] == 'maintenance') ? 'selected' : ''; ?>>Manutenção</option>
                        <option value="inactive" <?php echo ($tool['status'] == 'inactive') ? 'selected' : ''; ?>>Inativa</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end pt-3 border-top">
                <a href="<?php echo BASE_URL; ?>/?route=tools" class="btn btn-light border me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-2"></i>Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
