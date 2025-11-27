<?php
// Caminho: controllers/controller_cria_sala.php

require '../login_logout/auth_check.php';
require "../principal/db_connect.php";

session_start();
 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // CORREÇÃO: Adicionado ../views/
    header('Location: ../views/view_crud_salas.php');
    exit;
}

$nome_sala = trim($_POST['nome_sala'] ?? ''); 

if (empty($nome_sala)) {
    $erro = "O nome da sala é obrigatório.";
    // CORREÇÃO: Adicionado ../views/
    header('Location: ../views/view_crud_salas.php?erro=' . urlencode($erro));
    exit;
}

$queryInsert = "INSERT INTO sala (nome) VALUES (:nome)";

try {
    $stmt = $pdo->prepare($queryInsert);
    $stmt->bindValue(':nome', $nome_sala, PDO::PARAM_STR);
    $stmt->execute();
    
    $sucesso = "Sala **(" . htmlspecialchars($nome_sala) . ")** criada com sucesso!";
    // CORREÇÃO: Adicionado ../views/
    header('Location: ../views/view_crud_salas.php?sucesso=' . urlencode($sucesso));
    exit;
    
} catch (PDOException $e) {
    if ($e->getCode() === '23000') { 
        $erro = "Erro: Já existe uma sala com o nome '" . htmlspecialchars($nome_sala) . "'.";
    } else {
        error_log("Erro ao criar sala: " . $e->getMessage());
        $erro = "Erro interno ao criar sala.";
    }
    // CORREÇÃO: Adicionado ../views/
    header('Location: ../views/view_crud_salas.php?erro=' . urlencode($erro));
    exit;
}
?>