<?php
require '../login_logout/auth_check.php';
require "../principal/db_connect.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sucesso = $_GET['sucesso'] ?? '';
$erro = $_GET['erro'] ?? '';

try {
    $stmt = $pdo->query("SELECT * FROM sala ORDER BY nome ASC");
    $salas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $salas = [];
    $erro = "Erro no banco de dados.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Salas</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="container nav-container">
            <a class="brand" href="../principal/index.php">Sistema Escolar</a>
            <a href="../login_logout/logout.php" class="btn-logout">Sair</a>
        </div>
    </nav>

    <div class="container main-content">
        <h2>Gerenciamento de Salas</h2>

        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success">
                <?= $sucesso ?>
                <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($erro) ?>
                <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3>Cadastrar Nova Sala</h3>
            </div>
            <div class="card-body">
                <form action="controller_cria_sala.php" method="POST">
                    <div class="form-group">
                        <label for="nome_sala">Nome da Sala:</label>
                        <input type="text" id="nome_sala" name="nome_sala" placeholder="Ex: Laboratório 1" required>
                    </div>
                    <button type="submit" class="btn-save">Salvar Sala</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Salas Cadastradas</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome da Sala</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($salas) > 0): ?>
                            <?php foreach ($salas as $sala): ?>
                                <tr>
                                    <td><?= $sala['id'] ?></td>
                                    <td><?= htmlspecialchars($sala['nome']) ?></td>
                                    <td class="text-right">
                                        <a href="view_editar_sala.php?id=<?= $sala['id'] ?>" class="btn-action btn-edit">Editar</a>
                                        <a href="controller_deleta_sala.php?id=<?= $sala['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Confirma a exclusão?');">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">Nenhuma sala encontrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>