<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Termo de Responsabilidade - Empréstimo #<?php echo $loan['id']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .content { margin-bottom: 30px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .signatures { margin-top: 50px; display: flex; justify-content: space-between; }
        .signature-box { width: 45%; text-align: center; border-top: 1px solid #000; padding-top: 10px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Imprimir Termo</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Fechar</button>
    </div>

    <div class="header">
        <h2>TERMO DE RESPONSABILIDADE DE FERRAMENTAS</h2>
        <p>Empréstimo Nº: <strong><?php echo $loan['id']; ?></strong> | Data: <?php echo date('d/m/Y H:i', strtotime($loan['loan_date'])); ?></p>
    </div>

    <div class="content">
        <p>Eu, <strong><?php echo htmlspecialchars($loan['user_name']); ?></strong>, matrícula <strong><?php echo htmlspecialchars($loan['registration']); ?></strong>, do setor <strong><?php echo htmlspecialchars($loan['sector']); ?></strong>, declaro ter recebido as ferramentas listadas abaixo em perfeitas condições de uso e conservação.</p>
        
        <p>Comprometo-me a utilizá-las adequadamente para os fins a que se destinam e a devolvê-las na data prevista de <strong><?php echo date('d/m/Y', strtotime($loan['expected_return_date'])); ?></strong>, ou quando solicitadas pela empresa.</p>
        
        <p>Estou ciente de que serei responsabilizado por quaisquer danos causados por mau uso, perda ou extravio das mesmas.</p>
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
            <br>
            <strong><?php echo htmlspecialchars($loan['user_name']); ?></strong><br>
            Colaborador Responsável
        </div>
        <div class="signature-box">
            <br>
            <strong><?php echo htmlspecialchars($loan['operator_name']); ?></strong><br>
            Operador / Almoxarifado
        </div>
    </div>

</body>
</html>
