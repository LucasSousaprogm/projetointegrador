<?php
// Caminho: projeto integrador/controllers/controller_cria_funcionario.php

require '../login_logout/auth_check.php'; 
require "../principal/db_connect.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/view_cria_usuario.php');
    exit;
}

$cpf = trim($_POST['cpf'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$senha_crua = $_POST['senha'] ?? null; 

if (empty($cpf) || empty($nome) || empty($senha_crua)) {
    $erro = "Todos os campos são obrigatórios.";
    header('Location: ../views/view_cria_usuario.php?erro=' . urlencode($erro));
    exit;
}

// Criptografa a senha antes de salvar
$senha_hash = password_hash($senha_crua, PASSWORD_DEFAULT); 

$queryInsert = "INSERT INTO funcionario 
                (cpf, nome, senha) 
                VALUES 
                (:cpf, :nome, :senha_hash)";

try {
    $stmt = $pdo->prepare($queryInsert);

    $stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR); 
    $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':senha_hash', $senha_hash, PDO::PARAM_STR);
    
    $stmt->execute();
    
    // Sucesso: Redireciona para o login com mensagem de sucesso
    header('Location: ../views/view_login.php?sucesso_cadastro=' . urlencode($cpf));
    exit;
    
} catch (PDOException $e) {
    if ($e->getCode() === '23000') { 
        $erro = "Erro: Já existe um usuário com o CPF **" . htmlspecialchars($cpf) . "**.";
    } else {
        error_log("Erro ao criar funcionário: " . $e->getMessage());
        $erro = "Erro interno ao cadastrar usuário. Detalhes: " . $e->getMessage();
    }
    header('Location: ../views/view_cria_usuario.php?erro=' . urlencode($erro));
    exit;
}
?>