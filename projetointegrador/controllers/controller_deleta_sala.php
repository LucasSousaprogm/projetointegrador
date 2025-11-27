<?php
// Caminho: controllers/controller_deleta_sala.php

require '../login_logout/auth_check.php';
require "../principal/db_connect.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nome_sala = trim($_GET['nome_sala'] ?? '');

if (empty($nome_sala)) {
    $erro = "Nome da sala não fornecido para exclusão.";
    header('Location: ../views/view_crud_salas.php?erro=' . urlencode($erro));
    exit;
}

$queryDelete = "DELETE FROM sala WHERE nome = :nome";

try {
    $stmt = $pdo->prepare($queryDelete);
    $stmt->bindValue(':nome', $nome_sala, PDO::PARAM_STR);
    $stmt->execute();
    
    $linhasAfetadas = $stmt->rowCount();

    if ($linhasAfetadas > 0) {
        $sucesso = "Sala **(" . htmlspecialchars($nome_sala) . ")** excluída com sucesso.";
        header('Location: ../views/view_crud_salas.php?sucesso=' . urlencode($sucesso));
    } else {
        $erro = "Aviso: Nenhuma sala encontrada com o nome " . htmlspecialchars($nome_sala) . " para exclusão.";
        header('Location: ../views/view_crud_salas.php?erro=' . urlencode($erro));
    }
    exit;
    
} catch (PDOException $e) {
    // Código de erro '23000' indica restrição de chave estrangeira (Patrimônio ainda na sala)
    if ($e->getCode() === '23000' || strpos($e->getMessage(), 'foreign key constraint') !== false) { 
        $erro = "Erro: A sala **(" . htmlspecialchars($nome_sala) . ")** não pode ser excluída, pois contém patrimônios associados. Remova ou mova os patrimônios primeiro.";
    } else {
        error_log("Erro ao excluir sala: " . $e->getMessage());
        $erro = "Erro interno ao tentar excluir a sala.";
    }
    header('Location: ../views/view_crud_salas.php?erro=' . urlencode($erro));
    exit;
}
?>