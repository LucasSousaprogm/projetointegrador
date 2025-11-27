<?php
require '../principal/db_connect.php';

$mensagemSucesso = $_GET['sucesso'] ?? null;
$mensagemErro = $_GET['erro'] ?? null;
$resultSalas = [];

$querySalas = "SELECT nome FROM sala ORDER BY nome ASC";
try {
    $stmt = $pdo->query($querySalas);
    $resultSalas = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("<h1 style='color: red;'>Erro ao buscar salas: </h1>" . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Cadastrar Novo Patrimônio</title>
</head>

<body>
    <section class="principal">
        <div style="text-align: right; margin-bottom: 20px; font-size: 0.9em;">
            <span style="color: #666;">Usuário: <?= htmlspecialchars($_SESSION['username'] ?? 'N/A') ?></span> |
            <a href="../principal/index.php" class="btn-voltar-index">Voltar para Salas</a> |
            <a href="../login_logout/logout.php" class="btn-deletar">Sair</a>
        </div>
        
        <h1>Cadastrar Novo Patrimônio</h1>

        <?php if ($mensagemSucesso): ?>
            <div class="alerta-patrimonio-movido">
                Patrimônio **(Cód. <?= htmlspecialchars($mensagemSucesso) ?>)** cadastrado com sucesso!
                <span class="fechar" onclick="this.parentElement.style.display='none';">x</span>
            </div>
        <?php endif; ?>
        
        <?php if ($mensagemErro): ?>
            <div class="alerta-erro">
                <?= htmlspecialchars($mensagemErro) ?>
                <span class="fechar" onclick="this.parentElement.style.display='none';">x</span>
            </div>
        <?php endif; ?>

        <form method="POST" action="../controllers/controller_cria_patrimonio.php" class="form-crud">
            <label for="codigo">Código do Patrimônio (Número Único):</label>
            <input type="number" id="codigo" name="codigo" required>

            <label for="tipo">Tipo:</label>
            <input type="text" id="tipo" name="tipo" required>

            <label for="marca">Marca:</label>
            <input type="text" id="marca" name="marca">

            <label for="ano">Ano:</label>
            <input type="number" id="ano" name="ano" min="1900" max="<?= date('Y') ?>">
            
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" min="1" required>

            <label for="sala_de_origem">Sala de Origem:</label>
            <select name="sala_de_origem" id="sala_de_origem" required>
                <option value="">Selecione uma sala</option>
                <?php foreach ($resultSalas as $sala): ?>
                    <option value="<?= htmlspecialchars($sala) ?>">
                        <?= htmlspecialchars($sala) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label for="nome_sala">Sala Atual:</label>
            <select name="nome_sala" id="nome_sala" required>
                <option value="">Selecione a sala atual</option>
                <?php foreach ($resultSalas as $sala): ?>
                    <option value="<?= htmlspecialchars($sala) ?>">
                        <?= htmlspecialchars($sala) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-filtrar" style="width: 100%; margin-top: 20px;">
                Cadastrar Patrimônio
            </button>
        </form>
    </section>
</body>

</html>