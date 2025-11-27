<?php
require '../principal/db_connect.php';

$codigo = $_GET['codigo'] ?? null;
$mensagemSucesso = $_GET['sucesso'] ?? null;
$patrimonio = null;
$resultSalas = [];

if (!$codigo) {
    die("Erro: Código do patrimônio não fornecido para edição.");
}

$queryPatrimonio = "SELECT * FROM patrimonio WHERE codigo = :codigo";

try {
    $stmt = $pdo->prepare($queryPatrimonio);
    $stmt->bindValue(':codigo', $codigo, PDO::PARAM_INT);
    $stmt->execute();
    $patrimonio = $stmt->fetch();

    if (!$patrimonio) {
        die("Erro: Patrimônio com código " . htmlspecialchars($codigo) . " não encontrado.");
    }
    
    $querySalas = "SELECT nome FROM sala ORDER BY nome ASC";
    $stmtSalas = $pdo->query($querySalas);
    $resultSalas = $stmtSalas->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    die("<h1 style='color: red;'>Erro ao carregar dados para edição: </h1>" . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Editar Patrimônio Cód. <?= htmlspecialchars($codigo) ?></title>
</head>

<body>
    <section class="principal">
        <div style="text-align: right; margin-bottom: 20px; font-size: 0.9em;">
            <span style="color: #666;">Usuário: <?= htmlspecialchars($_SESSION['username'] ?? 'N/A') ?></span> |
            <a href="../principal/controller_lista_patrimonio.php" class="btn-voltar-index">Voltar para Lista</a> |
            <a href="../login_logout/logout.php" class="btn-deletar">Sair</a>
        </div>
        
        <h1>Editar Patrimônio (Cód. <?= htmlspecialchars($patrimonio['codigo']) ?>)</h1>

        <?php if ($mensagemSucesso): ?>
            <div class="alerta-patrimonio-movido">
                Patrimônio **(Cód. <?= htmlspecialchars($mensagemSucesso) ?>)** editado com sucesso!
                <span class="fechar" onclick="this.parentElement.style.display='none';">x</span>
            </div>
        <?php endif; ?>

        <form method="POST" action="../principal/controller_edita_patrimonio.php" class="form-crud">
            <input type="hidden" name="codigo" value="<?= htmlspecialchars($patrimonio['codigo']) ?>">
            
            <label for="tipo">Tipo:</label>
            <input type="text" id="tipo" name="tipo" value="<?= htmlspecialchars($patrimonio['tipo']) ?>" required>

            <label for="marca">Marca:</label>
            <input type="text" id="marca" name="marca" value="<?= htmlspecialchars($patrimonio['marca']) ?>">

            <label for="ano">Ano:</label>
            <input type="number" id="ano" name="ano" min="1900" max="<?= date('Y') ?>" value="<?= htmlspecialchars($patrimonio['ano']) ?>">
            
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" min="1" value="<?= htmlspecialchars($patrimonio['quantidade']) ?>" required>

            <label for="sala_de_origem">Sala de Origem:</label>
            <select name="sala_de_origem" id="sala_de_origem" required>
                <?php foreach ($resultSalas as $sala): ?>
                    <option value="<?= htmlspecialchars($sala) ?>"
                        <?= ($sala == $patrimonio['sala_de_origem']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sala) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label for="nome_sala">Sala Atual:</label>
            <select name="nome_sala" id="nome_sala" required>
                <?php foreach ($resultSalas as $sala): ?>
                    <option value="<?= htmlspecialchars($sala) ?>"
                        <?= ($sala == $patrimonio['nome_sala']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sala) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-filtrar" style="width: 100%; margin-top: 20px;">
                Salvar Alterações
            </button>
        </form>
    </section>
</body>

</html>