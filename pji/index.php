<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../views/view_login.php');
    exit;
}

require "db_connect.php"; 

$querySalas = "SELECT nome FROM sala ORDER BY nome ASC"; 
$resultSalas = [];
$errorMessage = null;

try {
    $stmt = $pdo->query($querySalas);
    $resultSalas = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $errorMessage = "<h1 style='color: red;'>Erro ao listar salas: </h1>" . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css"> 
    <title>Inventário - Escolha a Sala</title>
</head>
<body>
    <div class="lista-salas">
        <div style="text-align: right; margin-bottom: 20px; font-size: 0.9em;">
            <span style="color: #666;">Usuário: <?= htmlspecialchars($_SESSION['user_nome'] ?? 'N/A') ?></span> |
            
            <a href="../views/view_crud_salas.php" class="btn-voltar-index">Gerenciar Salas</a> | 
            <a href="../views/view_cria_patrimonio.php" class="btn-novo-patrimonio">Novo Patrimônio</a> |
            <a href="../controllers/controller_lista_patrimonio.php" class="btn-novo-patrimonio">Lista Completa</a> | 
            <a href="../login_logout/controller_logout.php" class="btn-deletar">Sair</a> 
        </div>
        
        <h1>Inventário de Patrimônio por Sala</h1>
        
        <?php if (isset($errorMessage)): ?>
            <?= $errorMessage ?>
        <?php elseif (count($resultSalas) > 0): ?>
            <p>Selecione uma sala para ver os patrimônios:</p>
            <div class="salas-container">
                <?php foreach ($resultSalas as $sala): ?>
                    <a href="controller_lista_sala.php?nome_sala=<?= urlencode($sala) ?>" class="sala-item">
                        <?= htmlspecialchars($sala) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Nenhuma sala cadastrada no momento.</p>
        <?php endif; ?>
    </div>
</body>
</html>