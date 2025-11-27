<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css"> 
    <title>Lista de Patrimônios Completa</title>
</head>

<body>
    <section class="principal">
        <div style="text-align: right; margin-bottom: 20px; font-size: 0.9em;">
            <span style="color: #666;">Usuário: <?= htmlspecialchars($_SESSION['username'] ?? 'N/A') ?></span> |
            <a href="../views/view_cria_patrimonio.php" class="btn-novo-patrimonio">Novo Patrimônio</a> |
            <a href="../principal/index.php" class="btn-voltar-index">Voltar para Salas</a> |
            <a href="../login_logout/logout.php" class="btn-deletar">Sair</a>
        </div>
        
        <h1>Lista de Patrimônio Completo</h1>

        <?php if (!empty($avisoBaixa)): ?>
            <div class="alerta-patrimonio-movido" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
                <?= htmlspecialchars($avisoBaixa) ?>
                <span class="fechar" onclick="this.parentElement.style.display='none';">x</span>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($erroBaixa)): ?>
            <div class="alerta-erro">
                <?= htmlspecialchars($erroBaixa) ?>
                <span class="fechar" onclick="this.parentElement.style.display='none';">x</span>
            </div>
        <?php endif; ?>

        <?php if (!empty($avisoEdicao)): ?>
            <div class="alerta-patrimonio-movido" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
                <?= htmlspecialchars($avisoEdicao) ?>
                <span class="fechar" onclick="this.parentElement.style.display='none';">x</span>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($erroEdicao)): ?>
            <div class="alerta-erro">
                <?= htmlspecialchars($erroEdicao) ?>
                <span class="fechar" onclick="this.parentElement.style.display='none';">x</span>
            </div>
        <?php endif; ?>

        <form method="GET" action="controller_lista_patrimonio.php" class="filtros">
            <div>
                <label for="codigo">Cód.:</label>
                <input type="number" id="codigo" name="codigo" value="<?= htmlspecialchars($filtros['codigo'] ?? '') ?>">
            </div>
            <div>
                <label for="tipo">Tipo:</label>
                <input type="text" id="tipo" name="tipo" value="<?= htmlspecialchars($filtros['tipo'] ?? '') ?>">
            </div>
            <div>
                <label for="marca">Marca:</label>
                <input type="text" id="marca" name="marca" value="<?= htmlspecialchars($filtros['marca'] ?? '') ?>">
            </div>
            <div>
                <label for="ano">Ano:</label>
                <input type="number" id="ano" name="ano" value="<?= htmlspecialchars($filtros['ano'] ?? '') ?>">
            </div>
            <div>
                <label for="sala_origem">Sala Origem:</label>
                <input type="text" id="sala_origem" name="sala_origem" value="<?= htmlspecialchars($filtros['sala_origem'] ?? '') ?>">
            </div>
            <div>
                <label for="nome_sala">Sala Atual:</label>
                <input type="text" id="nome_sala" name="nome_sala" value="<?= htmlspecialchars($filtros['nome_sala'] ?? '') ?>">
            </div>
            <button type="submit" class="btn-filtrar">Filtrar</button>
            <a href="controller_lista_patrimonio.php" class="btn-limpar">Limpar Filtros</a>
        </form>
        
        <?php if ($errorMessage): ?>
            <div class="alerta-erro">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php else: ?>
        <table class="principal-tabela">
            <thead>
                <tr>
                    <th>Cód.</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Ano</th>
                    <th>Qtd.</th>
                    <th>Sala de Origem</th>
                    <th>Sala Atual</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($resultPatrimonios) { 
                    foreach ($resultPatrimonios as $patrimonio) { ?>
                        <tr>
                            <td data-label="Cód."><?= htmlspecialchars($patrimonio['codigo']) ?></td>
                            <td data-label="Tipo" class="coluna-tipo"><?= htmlspecialchars($patrimonio['tipo']) ?></td>
                            <td data-label="Marca"><?= htmlspecialchars($patrimonio['marca']) ?></td>
                            <td data-label="Ano"><?= htmlspecialchars($patrimonio['ano']) ?></td>
                            <td data-label="Quantidade"><?= htmlspecialchars($patrimonio['quantidade']) ?></td>
                            <td data-label="Sala de Origem"><?= htmlspecialchars($patrimonio['sala_de_origem']) ?></td>
                            <td data-label="Sala Atual"><?= htmlspecialchars($patrimonio['nome_sala']) ?></td>
                            <td class="coluna-acoes">
                                <a href="../views/view_edita_patrimonio.php?codigo=<?= $patrimonio['codigo'] ?>" class="btn-editar">Editar</a>
                                <a href="controller_baixa_patrimonio.php?codigo=<?= $patrimonio['codigo'] ?>" class="btn-deletar" onclick="return confirm('Tem certeza que deseja dar Baixa (excluir) o patrimônio <?= $patrimonio['codigo'] ?>?')">Baixar</a>
                            </td>
                        </tr>
                <?php
                    }
                } else { ?>
                    <tr>
                        <td colspan="8">Nenhum patrimônio encontrado com os filtros aplicados.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php endif; ?>
    </section>
</body>

</html>