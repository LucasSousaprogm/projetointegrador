<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    header('Location: ../principal/index.php'); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? ''; 

    if ($action === 'login') {
        
        $cpf_digitado = trim($_POST['username'] ?? 'UsuarioDefault'); 
        
        
        $_SESSION['logado'] = true;
        
        $_SESSION['username'] = "Acesso Ilegal - CPF: {$cpf_digitado}"; 
        $_SESSION['user_cpf'] = $cpf_digitado; 
        
        header('Location: ../principal/index.php'); 
        exit;
        
    } 
    
    elseif ($action === 'cadastro') {
        $erro = urlencode("Cadastro temporariamente desabilitado para demonstração de falha de login.");
        header("Location: ../views/view_cria_usuario.php?erro=" . $erro);
        exit;
    }
    
    header('Location: ../views/view_login.php?erro=' . urlencode("Ação desconhecida."));
    exit;
} else {
    header('Location: ../views/view_login.php');
    exit;
}
