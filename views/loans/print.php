<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Termo de Responsabilidade - Empréstimo #<?php echo $loan['id']; ?></title>
    <style>
        :root {
            --text: #1f2933;
            --muted: #52606d;
            --border: #d5d7da;
            --accent: #0f6fff;
            --bg: #f7f8fa;
        }
        * { box-sizing: border-box; }
        body { font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif; font-size: 12pt; line-height: 1.6; margin: 0; padding: 28px; color: var(--text); background: white; }
        .page { max-width: 1100px; margin: 0 auto; background: #fff; }
        .actions { text-align: right; margin-bottom: 18px; }
        .btn { padding: 10px 18px; font-size: 14px; cursor: pointer; border: 1px solid var(--border); background: #fff; color: var(--text); border-radius: 6px; margin-left: 8px; }
        .btn-primary { background: var(--accent); color: #fff; border-color: var(--accent); }
        .header { text-align: center; margin-bottom: 26px; padding-bottom: 14px; border-bottom: 2px solid #000; }
        .header h2 { margin: 0 0 8px; letter-spacing: 0.5px; }
        .meta { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; color: var(--muted); font-size: 11pt; }
        .meta strong { color: var(--text); }
        .info-card { background: var(--bg); border: 1px solid var(--border); border-radius: 10px; padding: 16px 18px; margin-bottom: 18px; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; }
        .info-label { font-size: 10pt; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; }
        .info-value { font-weight: 600; color: var(--text); }
        .content { margin-bottom: 22px; }
        .content p { margin: 10px 0; text-align: justify; }
        h3 { margin: 24px 0 12px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 1px solid var(--border); }
        .table th, .table td { border: 1px solid var(--border); padding: 10px; text-align: left; font-size: 11pt; }
        .table th { background: var(--bg); font-weight: 700; }
        .signatures { margin-top: 48px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; }
        .signature-box { text-align: center; border-top: 1px solid #000; padding-top: 12px; }
        .signature-role { color: var(--muted); font-size: 10.5pt; }
        @media print {
            .no-print { display: none; }
            body { padding: 0 12px; }
            .page { max-width: 100%; }
        }
    </style>
</head>
<body>
<?php
    $expectedRaw = $loan['expected_return_date'] ?? null;
    $expectedDate = null;
    if (!empty($expectedRaw)) {
        $ts = strtotime($expectedRaw);
        if ($ts && $ts > 0) {
            $expectedDate = date('d/m/Y', $ts);
        }
    }
    $expectedText = $expectedDate ?: 'sob solicitação';
?>
    <div class="page">
        <div class="no-print actions">
            <button class="btn btn-primary" onclick="window.print()">Imprimir Termo</button>
            <button class="btn" onclick="window.close()">Fechar</button>
        </div>

        <div class="header">
            <h2>TERMO DE RESPONSABILIDADE DE FERRAMENTAS</h2>
            <div class="meta">
                <div>Empréstimo nº <strong><?php echo $loan['id']; ?></strong></div>
                <div>Data: <strong><?php echo date('d/m/Y H:i', strtotime($loan['loan_date'])); ?></strong></div>
                <?php if ($expectedDate): ?>
                    <div>Devolução prevista: <strong><?php echo $expectedDate; ?></strong></div>
                <?php else: ?>
                    <div>Devolução: <strong>sob solicitação</strong></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="info-card">
            <div class="info-grid">
                <div>
                    <div class="info-label">Colaborador</div>
                    <div class="info-value"><?php echo htmlspecialchars($loan['user_name']); ?></div>
                </div>
                <div>
                    <div class="info-label">Matrícula</div>
                    <div class="info-value"><?php echo htmlspecialchars($loan['registration']); ?></div>
                </div>
                <div>
                    <div class="info-label">Setor</div>
                    <div class="info-value"><?php echo htmlspecialchars($loan['sector']); ?></div>
                </div>
                <div>
                    <div class="info-label">Operador Responsável</div>
                    <div class="info-value"><?php echo htmlspecialchars($loan['operator_name']); ?></div>
                </div>
            </div>
        </div>

        <div class="content">
            <p>Declaro ter recebido as ferramentas listadas abaixo em perfeitas condições de uso e conservação.</p>
            <p>
                Comprometo-me a utilizá-las exclusivamente para os fins a que se destinam e a devolvê-las
                <?php if ($expectedDate): ?>
                    até <strong><?php echo $expectedDate; ?></strong>
                <?php else: ?>
                    sempre que solicitado pela empresa
                <?php endif; ?>.
            </p>
            <p>Estou ciente de que sou responsável pela guarda e conservação das ferramentas, respondendo por danos, mau uso, perda ou extravio.</p>
        </div>

        <h3>Itens do Empréstimo</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Nº Série</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['code']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><?php echo htmlspecialchars($item['serial_number']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="signatures">
            <div class="signature-box">
                <div><strong><?php echo htmlspecialchars($loan['user_name']); ?></strong></div>
                <div class="signature-role">Colaborador Responsável</div>
            </div>
            <div class="signature-box">
                <div><strong><?php echo htmlspecialchars($loan['operator_name']); ?></strong></div>
                <div class="signature-role">Operador / Almoxarifado</div>
            </div>
        </div>
    </div>
</body>
</html>
