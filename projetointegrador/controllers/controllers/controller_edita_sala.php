<?php
// Caminho: controllers/controller_edita_sala.php

require '../login_logout/auth_check.php';
require "../principal/db_connect.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/view_crud_salas.php');
    exit;
}

// 2. Recebe os dados do formulário POST
$nome_sala_antigo = trim($_POST['nome_sala_antigo'] ?? ''); // Nome atual (chave primária, veio do campo HIDDEN)
$nome_sala_novo = trim($_POST['nome_sala_novo'] ?? '');     // Novo nome (veio do campo de texto)

// 3. Validação básica
if (empty($nome_sala_antigo) || empty($nome_sala_novo)) {
    $erro = "Erro: Nome da sala antigo ou novo não fornecido.";
    header('Location: ../views/view_crud_salas.php?erro=' . urlencode($erro));
    exit;
}

if ($nome_sala_antigo === $nome_sala_novo) {
    $sucesso = "Nenhuma alteração detectada no nome da sala.";
    header('Location: ../views/view_crud_salas.php?sucesso=' . urlencode($sucesso));
    exit;
}

// 4. Inicia a Transação
// Essencial para garantir que se uma atualização falhar, a outra não seja aplicada.
$pdo->beginTransaction();

try {
    // Query 1: Atualiza o nome na tabela 'sala'
    $queryUpdateSala = "UPDATE sala SET nome = :nome_sala_novo WHERE nome = :nome_sala_antigo";
    $stmtSala = $pdo->prepare($queryUpdateSala);
    $stmtSala->bindValue(':nome_sala_novo', $nome_sala_novo, PDO::PARAM_STR);
    $stmtSala->bindValue(':nome_sala_antigo', $nome_sala_antigo, PDO::PARAM_STR);
    $stmtSala->execute();

    // Query 2: Atualiza a chave estrangeira na tabela 'patrimonio'
    $queryUpdatePatrimonio = "UPDATE patrimonio SET nome_sala = :nome_sala_novo WHERE nome_sala = :nome_sala_antigo";
    $stmtPatrimonio = $pdo->prepare($queryUpdatePatrimonio);
    $stmtPatrimonio->bindValue(':nome_sala_novo', $nome_sala_novo, PDO::PARAM_STR);
    $stmtPatrimonio->bindValue(':nome_sala_antigo', $nome_sala_antigo, PDO::PARAM_STR);
    $stmtPatrimonio->execute();

    // Confirma a transação
    $pdo->commit();

    $sucesso = "Sala **(" . htmlspecialchars($nome_sala_antigo) . ")** renomeada com sucesso para **(" . htmlspecialchars($nome_sala_novo) . ")** e patrimônios associados atualizados.";
    header('Location: ../views/view_crud_salas.php?sucesso=' . urlencode($sucesso));
    exit;
    
} catch (PDOException $e) {
    // Desfaz a transação em caso de erro
    $pdo->rollBack();

    if ($e->getCode() === '23000') { 
        // 23000 é o código para violação de chave única (nome_sala duplicado)
        $erro = "Erro: Já existe uma sala com o nome **(" . htmlspecialchars($nome_sala_novo) . ")**. Escolha outro nome.";
    } else {
        error_log("Erro ao editar sala: " . $e->getMessage());
        $erro = "Erro interno ao tentar renomear a sala. Detalhes: " . $e->getMessage();
    }
    
    // Redireciona com erro
    header('Location: ../views/view_crud_salas.php?erro=' . urlencode($erro));
    exit;
}
?>