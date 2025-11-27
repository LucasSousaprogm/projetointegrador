<?php
// Caminho: views/view_edita_sala.php

require '../login_logout/auth_check.php';
require "../principal/db_connect.php";

$nome_sala_antigo = $_GET['nome_sala'] ?? null;

if (empty($nome_sala_antigo)) {
    $erro = "Nome da sala não fornecido para edição.";
    header('Location: view_crud_salas.php?erro=' . urlencode($erro));
    exit;
}

// Verifica se a sala existe (opcional, mas recomendado)
try {
    $stmt = $pdo->prepare("SELECT nome FROM sala WHERE nome = :nome");
    $stmt->bindValue(':nome', $nome_sala_antigo, PDO::PARAM_STR);
    $stmt->execute();
    $sala = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sala) {
        $erro = "Sala '" . htmlspecialchars($nome_sala_antigo) . "' não encontrada.";
        header('Location: view_crud_salas.php?erro=' . urlencode($erro));
        exit;
    }

} catch (PDOException $e) {
    $erro = "Erro ao buscar dados da sala: " . $e->getMessage();
    header('Location: view_crud_salas.php?erro=' . urlencode($erro));
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sala</title>
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>

    <nav class="navbar">
        <div class="container nav-container">
            <a class="brand" href="../principal/index.php">Sistema Escolar</a>
            <a href="view_crud_salas.php" class="btn-voltar-index">← Voltar</a>
        </div>
    </nav>

    <div class="container main-content">
        <h2>Editar Sala: <?= htmlspecialchars($nome_sala_antigo) ?></h2>

        <div class="card">
            <div class="card-header">
                <h3>Renomear Sala</h3>
            </div>
            <div class="card-body">
                <form action="../controllers/controller_edita_sala.php" method="POST">
                    <input type="hidden" name="nome_sala_antigo" value="<?= htmlspecialchars($nome_sala_antigo) ?>">

                    <div class="form-group">
                        <label for="nome_sala_novo">Novo Nome da Sala:</label>
                        <input type="text" id="nome_sala_novo" name="nome_sala_novo" 
                               placeholder="Ex: Novo Laboratório 1" 
                               value="<?= htmlspecialchars($nome_sala_antigo) ?>" 
                               required>
                    </div>
                    <button type="submit" class="btn-save">Salvar Alteração</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>