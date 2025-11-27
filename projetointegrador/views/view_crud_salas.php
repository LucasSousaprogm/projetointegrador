<?php
// Caminho: views/view_crud_salas.php

require '../login_logout/auth_check.php';
require "../principal/db_connect.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Receber mensagens de sucesso/erro da URL
$sucesso = $_GET['sucesso'] ?? '';
$erro = $_GET['erro'] ?? '';

// 2. Buscar todas as salas
$salas = [];
try {
    // A query deve selecionar apenas 'nome', que é a chave primária
    $stmt = $pdo->query("SELECT nome FROM sala ORDER BY nome ASC"); 
    $salas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $salas = [];
    $erro = "Erro ao carregar dados: " . $e->getMessage();
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
            <a href="../login_logout/controller_logout.php" class="btn-logout">Sair</a>
        </div>
    </nav>

    <div class="container main-content">
        <h2>Gerenciamento de Salas</h2>

        <?php if (!empty($sucesso)): ?>
            <div class="alerta-sucesso">
                ✅ <?= nl2br(htmlspecialchars($sucesso)) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($erro)): ?>
            <div class="alerta-erro">
                🚫 <?= nl2br(htmlspecialchars($erro)) ?>
            </div>
        <?php endif; ?>
        
        <div class="card card-create">
            <div class="card-header">
                <h3>Cadastrar Nova Sala</h3>
            </div>
            <div class="card-body">
                <form action="../controllers/controller_cria_sala.php" method="POST" class="form-horizontal"> 
                    <div class="form-group">
                        <label for="nome_sala">Nome da Sala:</label>
                        <input type="text" id="nome_sala" name="nome_sala" 
                               placeholder="Ex: Laboratório de Informática" 
                               required>
                    </div>
                    
                    <button type="submit" class="btn-filtrar" style="width: 100%; margin-top: 10px;">
                        Cadastrar Sala
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Salas Cadastradas</h3>
            </div>
            <div class="card-body">
                <table class="principal-tabela">
                    <thead>
                        <tr>
                            <th style="width: 80%;">Nome da Sala</th>
                            <th style="width: 20%;" class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($salas) > 0): ?>
                            <?php foreach ($salas as $sala): ?>
                                <tr>
                                    <td data-label="Nome da Sala">
                                        <a href="../controllers/controller_lista_sala.php?nome_sala=<?= urlencode($sala['nome']) ?>" 
                                            class="link-sala">
                                            <?= htmlspecialchars($sala['nome']) ?>
                                        </a>
                                    </td>
                                    <td class="text-right coluna-acoes">
                                        <a href="view_edita_sala.php?nome_sala=<?= urlencode($sala['nome']) ?>" 
                                           class="btn-action btn-edit">
                                            Editar
                                        </a>
                                        
                                        <a href="../controllers/controller_deleta_sala.php?nome_sala=<?= urlencode($sala['nome']) ?>" 
                                           class="btn-action btn-delete" 
                                           onclick="return confirm('Confirma a exclusão da sala <?= htmlspecialchars($sala['nome']) ?>? Atenção: Só será possível excluir se não houverem patrimônios nesta sala.');">
                                            Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">Nenhuma sala encontrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>