<?php
// Caminho: views/view_move_patrimonio.php

require "../principal/db_connect.php";

$codigo = $_GET['codigo'] ?? null;
$salaOrigem = $_GET['sala_origem'] ?? null;

if (!$codigo || !$salaOrigem) {
    die("Erro: Código do patrimônio ou Sala de Origem não fornecidos.");
}

// Busca todas as salas, exceto a de origem
$querySalas = "SELECT nome FROM sala WHERE nome != :sala_origem ORDER BY nome ASC";
$resultSalas = [];
try {
    $stmt = $pdo->prepare($querySalas);
    $stmt->bindParam(':sala_origem', $salaOrigem, PDO::PARAM_STR);
    $stmt->execute();
    $resultSalas = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("<h1 style='color: red;'>Erro ao listar salas: </h1>" . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Mover Patrimônio Cód. <?= htmlspecialchars($codigo) ?></title>
</head>
<body>
    <section class="principal">
        
        <a href="../controllers/controller_lista_sala.php?nome_sala=<?= urlencode($salaOrigem) ?>" class="btn-voltar-index">← Voltar para Sala <?= htmlspecialchars($salaOrigem) ?></a>

        <div class="container-mover">
            <h1>Mover Patrimônio (Cód. <?= htmlspecialchars($codigo) ?>)</h1>
            <p>Sala Atual: <strong><?= htmlspecialchars($salaOrigem) ?></strong></p>

            <form method="POST" action="../controllers/controller_move_patrimonio.php">
                <input type="hidden" name="codigo" value="<?= htmlspecialchars($codigo) ?>">
                <input type="hidden" name="sala_origem" value="<?= htmlspecialchars($salaOrigem) ?>">

                <label for="nova_sala">Mover para qual Sala?</label>
                <select name="nova_sala" id="nova_sala" required>
                    <option value="">Selecione a Sala</option>
                    <?php foreach ($resultSalas as $sala): ?>
                        <option value="<?= htmlspecialchars($sala) ?>">
                            <?= htmlspecialchars($sala) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" class="btn-filtrar" style="width: 100%; margin-top: 20px;">
                    Confirmar Movimento
                </button>
            </form>
        </div>
    </section>
</body>
</html>