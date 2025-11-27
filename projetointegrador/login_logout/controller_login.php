<?php
// Caminho: projeto integrador/login_logout/controller_login.php

require '../principal/db_connect.php';
//require '../login_logout/auth_check.php';

// Inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se o usuário já estiver logado, redireciona para a página principal
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    header('Location: ../principal/index.php');
    exit;
}

// Verifica se o formulário foi enviado (método POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/view_login.php'); 
    exit;
}

$cpf = trim($_POST['cpf'] ?? '');
$senha_crua = $_POST['senha'] ?? '';

if (empty($cpf) || empty($senha_crua)) {
    $erro = "CPF e senha são obrigatórios.";
    header('Location: ../views/view_login.php?erro=' . urlencode($erro));
    exit;
}


$querySelect = "SELECT cpf, nome, senha FROM funcionario WHERE cpf = :cpf";

try {
    $stmt = $pdo->prepare($querySelect);
    $stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR);
    $stmt->execute();
    $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($funcionario) {
        
        // 1. CORREÇÃO PRINCIPAL: Usa 'password_verify' para checar a senha hasheada.
        if (password_verify($senha_crua, $funcionario['senha'])) {
            
            // Login bem-sucedido: Inicia a sessão
            $_SESSION['logado'] = true;
            $_SESSION['user_cpf'] = $funcionario['cpf'];
            $_SESSION['user_nome'] = $funcionario['nome']; 
            
            // 2. CORREÇÃO DE REDIRECIONAMENTO: Redireciona para o ponto de entrada principal (index.php)
            header('Location: ../principal/index.php'); 
            exit;
        }
    }

 
    $erro = "CPF ou senha inválidos.";
    header('Location: ../views/view_login.php?erro=' . urlencode($erro));
    exit;

} catch (PDOException $e) {
    error_log("Erro no login: " . $e->getMessage());
    $erro = "Erro interno ao tentar fazer login. Tente novamente.";
    header('Location: ../views/view_login.php?erro=' . urlencode($erro));
    exit;
}
?>