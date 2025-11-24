<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Registrar Empréstimo</h2>
        <p class="text-secondary mb-0">Novo registro de saída de ferramentas</p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>/?route=loans" class="btn btn-outline-secondary">
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

<form action="<?php echo BASE_URL; ?>/?route=loans_store" method="POST" id="loanForm">
    <?php echo csrf_field(); ?>
    <div class="row g-4">
        <!-- Coluna da Esquerda: Dados do Empréstimo -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm-custom h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-person-badge me-2 text-primary"></i>Dados do Colaborador</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label for="user_id" class="form-label fw-medium">Colaborador</label>
                        <select class="form-select form-select-lg" id="user_id" name="user_id" required>
                            <option value="">Selecione o colaborador...</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['name']); ?> (<?php echo $u['registration']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text"><i class="bi bi-qr-code-scan me-1"></i>Você pode bipar o crachá com o campo focado.</div>
                    </div>

                    <div class="mb-4">
                        <label for="tool_codes_display" class="form-label fw-medium">Ferramentas Selecionadas</label>
                        <textarea class="form-control font-monospace bg-light" id="tool_codes_display" rows="6" readonly required placeholder="Selecione as ferramentas ao lado..."></textarea>
                        <input type="hidden" id="tool_codes" name="tool_codes">
                        <div class="form-text text-end" id="countTools">0 ferramentas selecionadas</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg py-3">
                            <i class="bi bi-check-circle-fill me-2"></i>Confirmar Empréstimo
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna da Direita: Seleção de Ferramentas -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm-custom h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-tools me-2 text-secondary"></i>Buscar Ferramentas</h5>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill"><?php echo count($availableTools); ?> disponíveis</span>
                </div>
                <div class="card-body p-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-secondary"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" id="searchTool" placeholder="Digite código, nome ou modelo...">
                    </div>

                    <div class="list-group overflow-auto custom-scrollbar" style="max-height: 500px;" id="toolsList">
                        <?php foreach ($availableTools as $tool): ?>
                            <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 tool-item" 
                                    data-code="<?php echo $tool['code']; ?>" 
                                    data-description="<?php echo htmlspecialchars($tool['description']); ?>"
                                    data-max-qty="<?php echo $tool['available_quantity']; ?>"
                                    data-search="<?php echo strtolower($tool['code'] . ' ' . $tool['description'] . ' ' . $tool['model_name']); ?>">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-light text-dark border me-2 font-monospace"><?php echo $tool['code']; ?></span>
                                        <span class="fw-bold text-dark"><?php echo htmlspecialchars($tool['description']); ?></span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($tool['model_name']); ?> | 
                                        <i class="bi bi-building me-1"></i><?php echo htmlspecialchars($tool['manufacturer']); ?>
                                    </small>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="text-center">
                                        <div class="badge bg-success bg-opacity-10 text-success border border-success">
                                            <i class="bi bi-box-seam me-1"></i><?php echo $tool['available_quantity']; ?> disp.
                                        </div>
                                    </div>
                                    <i class="bi bi-plus-circle fs-4 text-primary action-icon"></i>
                                </div>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal para Selecionar Quantidade -->
<div class="modal fade" id="quantityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-box-seam me-2"></i>Selecionar Quantidade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Ferramenta: <strong id="modalToolCode"></strong></p>
                <div class="mb-3">
                    <label for="quantityInput" class="form-label">Quantidade a emprestar</label>
                    <input type="number" class="form-control form-control-lg text-center" id="quantityInput" min="1" value="1">
                    <div class="form-text">Máximo disponível: <strong id="modalMaxQty"></strong></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmQuantity">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchTool');
    const toolItems = document.querySelectorAll('.tool-item');
    const displayTextarea = document.getElementById('tool_codes_display');
    const hiddenInput = document.getElementById('tool_codes');
    const countDisplay = document.getElementById('countTools');
    const quantityModal = new bootstrap.Modal(document.getElementById('quantityModal'));
    const quantityInput = document.getElementById('quantityInput');
    const confirmBtn = document.getElementById('confirmQuantity');
    
    let selectedTools = new Map(); // code => { quantity, name }
    let currentTool = null;

    // Filtro de busca
    searchInput.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        toolItems.forEach(item => {
            const searchData = item.getAttribute('data-search');
            if (searchData.includes(term)) {
                item.classList.remove('d-none');
            } else {
                item.classList.add('d-none');
            }
        });
    });

    // Seleção de ferramentas
    toolItems.forEach(item => {
        item.addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            const name = this.getAttribute('data-description');
            const maxQty = parseInt(this.getAttribute('data-max-qty'));
            
            if (selectedTools.has(code)) {
                // Remover
                selectedTools.delete(code);
                this.classList.remove('active', 'bg-primary', 'bg-opacity-10');
                const icon = this.querySelector('.action-icon');
                icon.classList.remove('bi-check-circle-fill', 'text-success');
                icon.classList.add('bi-plus-circle', 'text-primary');
                updateTextarea();
            } else {
                // Abrir modal para selecionar quantidade
                currentTool = {code, maxQty, name, element: this};
                document.getElementById('modalToolCode').textContent = code;
                document.getElementById('modalMaxQty').textContent = maxQty;
                quantityInput.value = 1;
                quantityInput.max = maxQty;
                quantityModal.show();
            }
        });
    });

    // Confirmar quantidade
    confirmBtn.addEventListener('click', function() {
        if (!currentTool) return;
        
        const qty = parseInt(quantityInput.value);
        if (qty < 1 || qty > currentTool.maxQty) {
            alert('Quantidade inválida!');
            return;
        }
        
        // Adicionar ferramenta com quantidade
        selectedTools.set(currentTool.code, { quantity: qty, name: currentTool.name });
        currentTool.element.classList.add('active', 'bg-primary', 'bg-opacity-10');
        const icon = currentTool.element.querySelector('.action-icon');
        icon.classList.remove('bi-plus-circle', 'text-primary');
        icon.classList.add('bi-check-circle-fill', 'text-success');
        
        updateTextarea();
        quantityModal.hide();
        currentTool = null;
    });

    function updateTextarea() {
        const entries = Array.from(selectedTools.entries());
        // Formato exibido: Nome: QTD | Valor enviado: CODIGO:QTD
        const displayText = entries.map(([, data]) => `${data.name}: ${data.quantity}`).join('\n');
        const payload = entries.map(([code, data]) => `${code}:${data.quantity}`).join(' ');
        displayTextarea.value = displayText;
        hiddenInput.value = payload;
        countDisplay.textContent = entries.length + (entries.length === 1 ? ' ferramenta selecionada' : ' ferramentas selecionadas');
    }
});
</script>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
.tool-item.active {
    background-color: #eef2ff !important; /* Indigo 50 */
    border-color: #c7d2fe !important;
}
</style>
