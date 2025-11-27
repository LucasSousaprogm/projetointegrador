<?php
require __DIR__ . '/../db_connect.php';
require '../login_logout/auth_check.php';

session_start();
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    header('Location: index.php');
    exit;
}

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
        if (password_verify($senha_crua, $funcionario['senha'])) {
            
            // Login bem-sucedido: Inicia a sessão
            $_SESSION['logado'] = true;
            $_SESSION['user_cpf'] = $funcionario['cpf'];
            $_SESSION['user_nome'] = $funcionario['nome']; 
            
            
            header('Location: index.php'); 
            exit;
        }
    }

 
    $erro = "CPF ou senha inválidos.";
    header('Location: ../views/view_login.php?erro=' . urlencode($erro));
    exit;

} catch (PDOException $e) {
    error_log("Erro ao tentar login: " . $e->getMessage());
    $erro = "Erro interno no sistema. Tente novamente.";
    header('Location: ../views/view_login.php?erro=' . urlencode($erro));
    exit;
}
?>