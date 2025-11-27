<?php
// Caminho: controllers/controller_edita_sala.php

require '../login_logout/auth_check.php';
require "../principal/db_connect.php";

session_start();
 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/view_crud_salas.php');
    exit;
}

$nome_sala_antigo = trim($_POST['nome_sala_antigo'] ?? '');
$nome_sala_novo = trim($_POST['nome_sala_novo'] ?? '');

if (empty($nome_sala_antigo) || empty($nome_sala_novo)) {
    $erro = "Os nomes da sala (antigo e novo) são obrigatórios.";
    header('Location: ../views/view_crud_salas.php?erro=' . urlencode($erro));
    exit;
}

// 1. Atualizar a tabela 'sala'
$queryUpdateSala = "UPDATE sala SET nome = :nome_sala_novo WHERE nome = :nome_sala_antigo";

// 2. Atualizar a tabela 'patrimonio' (Chave Estrangeira)
$queryUpdatePatrimonio = "UPDATE patrimonio SET nome_sala = :nome_sala_novo WHERE nome_sala = :nome_sala_antigo";

try {
    // Inicia a transação para garantir que ambas as atualizações ocorram
    $pdo->beginTransaction();

    // Atualiza a tabela 'sala'
    $stmtSala = $pdo->prepare($queryUpdateSala);
    $stmtSala->bindValue(':nome_sala_novo', $nome_sala_novo, PDO::PARAM_STR);
    $stmtSala->bindValue(':nome_sala_antigo', $nome_sala_antigo, PDO::PARAM_STR);
    $stmtSala->execute();

    // Atualiza a tabela 'patrimonio'
    $stmtPatrimonio = $pdo->prepare($queryUpdatePatrimonio);
    $stmtPatrimonio->bindValue(':nome_sala_novo', $nome_sala_novo, PDO::PARAM_STR);
    $stmtPatrimonio->bindValue(':nome_sala_antigo', $nome_sala_antigo, PDO::PARAM_STR);
    $stmtPatrimonio->execute();

    // Confirma as alterações
    $pdo->commit();
    
    $sucesso = "Sala **(" . htmlspecialchars($nome_sala_antigo) . ")** renomeada para **(" . htmlspecialchars($nome_sala_novo) . ")** com sucesso!";
    header('Location: ../views/view_crud_salas.php?sucesso=' . urlencode($sucesso));
    exit;
    
} catch (PDOException $e) {
    // Reverte se houver erro
    $pdo->rollBack();

    if ($e->getCode() === '23000') { 
        $erro = "Erro: Já existe uma sala com o nome '" . htmlspecialchars($nome_sala_novo) . "'.";
    } else {
        error_log("Erro ao editar sala: " . $e->getMessage());
        $erro = "Erro interno ao editar sala. Detalhes: " . $e->getMessage();
    }
    header('Location: ../views/view_crud_salas.php?erro=' . urlencode($erro));
    exit;
}
?>