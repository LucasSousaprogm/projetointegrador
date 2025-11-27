<?php
require '../login_logout/auth_check.php';
require "../principal/db_connect.php";

session_start(); 

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../views/view_login.php'); // Redireciona para o login
    exit;
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Garante que o usuário veio do formulário
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

// 1. Criptografa a senha antes de salvar
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
    
    // Sucesso: Redireciona de volta para a view de cadastro com mensagem de sucesso
    header('Location: ../views/view_cria_usuario.php?sucesso=' . urlencode($cpf));
    exit;
    
} catch (PDOException $e) {
    // 23000 é geralmente o código de erro para violação de chave única (CPF duplicado)
    if ($e->getCode() === '23000') { 
        $erro = "Erro: Já existe um usuário cadastrado com o CPF: " . htmlspecialchars($cpf) . ".";
    } else {
        error_log("Erro ao cadastrar funcionário: " . $e->getMessage());
        $erro = "Erro interno. Tente novamente.";
    }
    header('Location: ../views/view_cria_usuario.php?erro=' . urlencode($erro));
    exit;
}
?>