<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    // Redireciona para a tela de login
    header('Location: ../views/view_login.php'); 
    exit;
}
?>