<?php
// Caminho: projeto integrador/controllers/controller_move_patrimonio.php

require '../login_logout/auth_check.php';
require "../principal/db_connect.php"; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Não é possível redirecionar para a view de lista de salas sem o nome da sala de origem. 
    header('Location: ../principal/index.php');
    exit;
}

$codigo = $_POST['codigo'] ?? null;
$salaOrigem = $_POST['sala_origem'] ?? null;
$novaSala = $_POST['nova_sala'] ?? null;

if (empty($codigo) || empty($salaOrigem) || empty($novaSala)) {
    $erro = "Erro: Dados incompletos para mover patrimônio.";
    header('Location: controller_lista_sala.php?nome_sala=' . urlencode($salaOrigem) . '&erro_movimento=' . urlencode($erro));
    exit;
}

if ($salaOrigem === $novaSala) {
    $erro = "Erro: A sala de destino deve ser diferente da sala de origem.";
    header('Location: controller_lista_sala.php?nome_sala=' . urlencode($salaOrigem) . '&erro_movimento=' . urlencode($erro));
    exit;
}

$queryUpdate = "UPDATE patrimonio SET nome_sala = :nova_sala WHERE codigo = :codigo AND nome_sala = :sala_origem";

try {
    $stmt = $pdo->prepare($queryUpdate);
    $stmt->bindValue(':nova_sala', $novaSala, PDO::PARAM_STR);
    $stmt->bindValue(':codigo', $codigo, PDO::PARAM_INT);
    $stmt->bindValue(':sala_origem', $salaOrigem, PDO::PARAM_STR);
    
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $mensagemSucesso = "Patrimônio **(Cód. " . htmlspecialchars($codigo) . ")** movido de **" . htmlspecialchars($salaOrigem) . "** para **" . htmlspecialchars($novaSala) . "** com sucesso!";
        header('Location: controller_lista_sala.php?nome_sala=' . urlencode($salaOrigem) . '&aviso_movimento=' . urlencode($mensagemSucesso));
    } else {
        $erro = "Erro: Não foi possível mover o patrimônio. Verifique se o código e a sala de origem estão corretos.";
        header('Location: controller_lista_sala.php?nome_sala=' . urlencode($salaOrigem) . '&erro_movimento=' . urlencode($erro));
    }
    exit;

} catch (PDOException $e) {
    error_log("Erro ao mover patrimônio: " . $e->getMessage());
    $erro = "Erro interno ao mover patrimônio. Tente novamente.";
    header('Location: controller_lista_sala.php?nome_sala=' . urlencode($salaOrigem) . '&erro_movimento=' . urlencode($erro));
    exit;
}
?>