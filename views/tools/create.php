<div class="row mb-3">
    <div class="col-md-12">
        <h2>Nova Ferramenta</h2>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>/?route=tools_store" method="POST">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="code" class="form-label">Código Interno</label>
                    <input type="text" class="form-control" id="code" name="code" required placeholder="FER-000">
                </div>
                <div class="col-md-9">
                    <label for="description" class="form-label">Descrição</label>
                    <input type="text" class="form-control" id="description" name="description" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Categoria</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="model_id" class="form-label">Modelo</label>
                    <select class="form-select" id="model_id" name="model_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($models as $mod): ?>
                            <option value="<?php echo $mod['id']; ?>"><?php echo htmlspecialchars($mod['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="manufacturer" class="form-label">Fabricante</label>
                    <input type="text" class="form-control" id="manufacturer" name="manufacturer">
                </div>
                <div class="col-md-4">
                    <label for="serial_number" class="form-label">Número de Série</label>
                    <input type="text" class="form-control" id="serial_number" name="serial_number">
                </div>
                <div class="col-md-4">
                    <label for="location" class="form-label">Localização</label>
                    <input type="text" class="form-control" id="location" name="location" placeholder="Ex: Prateleira A1">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="acquisition_date" class="form-label">Data de Aquisição</label>
                    <input type="date" class="form-control" id="acquisition_date" name="acquisition_date">
                </div>
                <div class="col-md-4">
                    <label for="quantity" class="form-label">Quantidade <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
                    <div class="form-text">Quantidade total disponível</div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?php echo BASE_URL; ?>/?route=tools" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>
