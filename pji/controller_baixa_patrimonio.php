<?php
require '../login_logout/auth_check.php';
require "../principal/db_connect.php"; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$codigo = $_GET['codigo'] ?? null;
$salaAtual = $_GET['sala_atual'] ?? null;

if (empty($codigo)) {
    $erro = "Erro: Código do patrimônio não fornecido para baixa.";
    if (!empty($salaAtual)) {
        header('Location: controller_lista_sala.php?nome_sala=' . urlencode($salaAtual) . '&erro_baixa=' . urlencode($erro));
    } else {
        header('Location: controller_lista_patrimonio.php?erro_baixa=' . urlencode($erro));
    }
    exit;
}

$queryDelete = "DELETE FROM patrimonio WHERE codigo = :codigo";

try {
    $stmt = $pdo->prepare($queryDelete);
    $stmt->bindValue(':codigo', $codigo, PDO::PARAM_INT);
    $stmt->execute();
    
    $linhasAfetadas = $stmt->rowCount();

    if ($linhasAfetadas > 0) {
        $mensagemSucesso = "Patrimônio (Cód. " . htmlspecialchars($codigo) . ") baixado/excluído com sucesso.";
    } else {
        $mensagemSucesso = "Aviso: Nenhum patrimônio encontrado com o código " . htmlspecialchars($codigo) . " para baixa.";
    }

    if (!empty($salaAtual)) {
        header('Location: controller_lista_sala.php?nome_sala=' . urlencode($salaAtual) . '&aviso_baixa=' . urlencode($mensagemSucesso));
    } else {
        header('Location: controller_lista_patrimonio.php?aviso_baixa=' . urlencode($mensagemSucesso));
    }
    exit;
    
} catch (PDOException $e) {
    error_log("Erro ao excluir patrimônio: " . $e->getMessage());
    $erro = "Erro interno ao dar baixa no patrimônio. Tente novamente.";

    if (!empty($salaAtual)) {
        header('Location: controller_lista_sala.php?nome_sala=' . urlencode($salaAtual) . '&erro_baixa=' . urlencode($erro));
    } else {
        header('Location: controller_lista_patrimonio.php?erro_baixa=' . urlencode($erro));
    }
    exit;
}