<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Ferramentas</h2>
        <p class="text-secondary mb-0">Gerencie o inventário de ferramentas</p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>/?route=tools_create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Nova Ferramenta
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm-custom mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="bi bi-funnel me-2"></i>Busca Avançada</h6>
        <button class="btn btn-sm btn-outline-secondary" type="button" onclick="document.getElementById('advancedFilters').classList.toggle('d-none')">
            <i class="bi bi-sliders me-1"></i>Filtros
        </button>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo BASE_URL; ?>/" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="route" value="tools">
            
            <!-- Busca Básica -->
            <div class="col-md-4">
                <label class="form-label text-secondary small fw-bold text-uppercase">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" name="search" placeholder="Código ou descrição..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
            </div>
            
            <div class="col-md-2">
                <label class="form-label text-secondary small fw-bold text-uppercase">Status</label>
                <select class="form-select" name="status">
                    <option value="">Todos</option>
                    <option value="available" <?php echo (isset($_GET['status']) && $_GET['status'] == 'available') ? 'selected' : ''; ?>>Disponível</option>
                    <option value="borrowed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'borrowed') ? 'selected' : ''; ?>>Emprestada</option>
                    <option value="maintenance" <?php echo (isset($_GET['status']) && $_GET['status'] == 'maintenance') ? 'selected' : ''; ?>>Manutenção</option>
                    <option value="inactive" <?php echo (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : ''; ?>>Inativa</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label text-secondary small fw-bold text-uppercase">Registros</label>
                <div class="form-check pt-2">
                    <input class="form-check-input" type="checkbox" value="1" id="include_deleted" name="include_deleted" <?php echo isset($_GET['include_deleted']) && $_GET['include_deleted'] == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label small" for="include_deleted">
                        Incluir arquivadas
                    </label>
                </div>
            </div>
            
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Buscar
                </button>
            </div>
            
            <div class="col-md-2">
                <a href="<?php echo BASE_URL; ?>/?route=tools" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle me-1"></i>Limpar
                </a>
            </div>
            
            <div class="col-md-2">
                <button type="button" class="btn btn-success w-100" onclick="exportToExcel()">
                    <i class="bi bi-file-earmark-excel me-1"></i>Exportar
                </button>
            </div>
            
            <!-- Filtros Avançados (Ocultos por padrão) -->
            <div id="advancedFilters" class="col-12 d-none">
                <div class="row g-3 pt-3 border-top">
                    <div class="col-md-3">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Categoria</label>
                        <select class="form-select" name="category">
                            <option value="">Todas</option>
                            <?php
                            $categories = $this->pdo->query("SELECT * FROM tool_categories ORDER BY name")->fetchAll();
                            foreach ($categories as $cat):
                            ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Fabricante</label>
                        <input type="text" class="form-control" name="manufacturer" placeholder="Nome do fabricante..." value="<?php echo isset($_GET['manufacturer']) ? htmlspecialchars($_GET['manufacturer']) : ''; ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Localização</label>
                        <input type="text" class="form-control" name="location" placeholder="Local..." value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Ordenar Por</label>
                        <select class="form-select" name="sort">
                            <option value="code" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'code') ? 'selected' : ''; ?>>Código</option>
                            <option value="description" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'description') ? 'selected' : ''; ?>>Descrição</option>
                            <option value="category" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'category') ? 'selected' : ''; ?>>Categoria</option>
                            <option value="recent" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'recent') ? 'selected' : ''; ?>>Mais Recentes</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function exportToExcel() {
    const table = document.querySelector('.table');
    let html = table.outerHTML;
    const url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'ferramentas_' + new Date().toISOString().slice(0,10) + '.xls';
    link.click();
}
</script>

<div class="card border-0 shadow-sm-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Código</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Local</th>
                        <th>Quantidade</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tools)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Nenhuma ferramenta encontrada
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tools as $tool): ?>
                        <tr>
                            <td class="ps-4"><span class="font-monospace fw-bold text-dark"><?php echo htmlspecialchars($tool['code']); ?></span></td>
                            <td>
                                <div class="fw-medium text-dark"><?php echo htmlspecialchars($tool['description']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($tool['manufacturer']); ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($tool['category_name']); ?></span></td>
                            <td><small class="text-secondary"><i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($tool['location']); ?></small></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                        <i class="bi bi-box-seam me-1"></i><?php echo $tool['available_quantity']; ?>
                                    </span>
                                    <small class="text-muted">/ <?php echo $tool['quantity']; ?></small>
                                </div>
                            </td>
                            <td>
                                <?php 
                                    if (!empty($tool['deleted_at'])) {
                                        $statusClass = 'secondary';
                                        $statusIcon = 'archive';
                                        $statusLabel = 'Arquivada';
                                    } else {
                                        $statusClass = 'secondary';
                                        $statusIcon = 'circle';
                                        $statusLabel = $tool['status'];
                                        switch($tool['status']) {
                                            case 'available': 
                                                $statusClass = 'success'; 
                                                $statusIcon = 'check-circle';
                                                $statusLabel = 'Disponível'; 
                                                break;
                                            case 'borrowed': 
                                                $statusClass = 'warning'; 
                                                $statusIcon = 'clock';
                                                $statusLabel = 'Emprestada'; 
                                                break;
                                            case 'maintenance': 
                                                $statusClass = 'danger'; 
                                                $statusIcon = 'wrench';
                                                $statusLabel = 'Manutenção'; 
                                                break;
                                        }
                                    }
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?> bg-opacity-10 text-<?php echo $statusClass; ?> border border-<?php echo $statusClass; ?> border-opacity-10 rounded-pill px-3">
                                    <i class="bi bi-<?php echo $statusIcon; ?> me-1"></i> <?php echo $statusLabel; ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="<?php echo BASE_URL; ?>/?route=tools_view&id=<?php echo $tool['id']; ?>" class="btn btn-sm btn-light text-secondary" title="Detalhes"><i class="bi bi-eye"></i></a>
                                    <?php if (empty($tool['deleted_at'])): ?>
                                        <a href="<?php echo BASE_URL; ?>/?route=tools_edit&id=<?php echo $tool['id']; ?>" class="btn btn-sm btn-light text-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                        <form action="<?php echo BASE_URL; ?>/?route=tools_delete" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo $tool['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Arquivar" onclick="return confirm('Arquivar esta ferramenta?');">
                                                <i class="bi bi-archive"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?php echo BASE_URL; ?>/?route=tools_restore" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo $tool['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-light text-success" title="Restaurar">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
