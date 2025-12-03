<?php
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

$queryUpdateSala = "UPDATE sala SET nome = :nome_sala_novo WHERE nome = :nome_sala_antigo";

$queryUpdatePatrimonio = "UPDATE patrimonio SET nome_sala = :nome_sala_novo WHERE nome_sala = :nome_sala_antigo";

try {
    $pdo->beginTransaction();

    $stmtSala = $pdo->prepare($queryUpdateSala);
    $stmtSala->bindValue(':nome_sala_novo', $nome_sala_novo, PDO::PARAM_STR);
    $stmtSala->bindValue(':nome_sala_antigo', $nome_sala_antigo, PDO::PARAM_STR);
    $stmtSala->execute();

    $stmtPatrimonio = $pdo->prepare($queryUpdatePatrimonio);
    $stmtPatrimonio->bindValue(':nome_sala_novo', $nome_sala_novo, PDO::PARAM_STR);
    $stmtPatrimonio->bindValue(':nome_sala_antigo', $nome_sala_antigo, PDO::PARAM_STR);
    $stmtPatrimonio->execute();

    $pdo->commit();
    
    $sucesso = "Sala **(" . htmlspecialchars($nome_sala_antigo) . ")** renomeada para **(" . htmlspecialchars($nome_sala_novo) . ")** com sucesso!";
    header('Location: ../views/view_crud_salas.php?sucesso=' . urlencode($sucesso));
    exit;
    
} catch (PDOException $e) {
    $pdo->rollBack();

    if ($e->getCode() === '23000' && strpos($e->getMessage(), 'Duplicate entry') !== false) { 
        $erro = "Erro: O novo nome de sala **(" . htmlspecialchars($nome_sala_novo) . ")** já existe.";
    } else {
        error_log("Erro ao renomear sala: " . $e->getMessage());
        $erro = "Erro interno ao renomear sala. Tente novamente.";
    }
    header('Location: ../views/view_crud_salas.php?erro=' . urlencode($erro));
    exit;
}