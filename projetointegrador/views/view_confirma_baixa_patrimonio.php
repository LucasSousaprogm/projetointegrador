<?php
// Caminho: projeto integrador/views/view_confirma_baixa_patrimonio.php

require '../login_logout/auth_check.php';
require "../principal/db_connect.php";
// session_start() é redundante se já estiver no auth_check.php, mas mantido.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$codigo = $_GET['codigo'] ?? null;
$salaAtual = $_GET['sala_atual'] ?? null;

if (empty($codigo)) {
    $erro = "Erro: Código do patrimônio não fornecido para baixa.";
    if (!empty($salaAtual)) {
        // Redirecionamento 1 CORRIGIDO
        header('Location: ../controllers/controller_lista_sala.php?nome_sala=' . urlencode($salaAtual) . '&erro_baixa=' . urlencode($erro));
    } else {
        // Redirecionamento 2 CORRIGIDO
        header('Location: ../controllers/controller_lista_patrimonio.php?erro_baixa=' . urlencode($erro));
    }
    exit;
}
// ... (Resto do código PHP para buscar o patrimônio)
$queryPatrimonio = "SELECT tipo, marca, nome_sala FROM patrimonio WHERE codigo = :codigo";

try {
    $stmt = $pdo->prepare($queryPatrimonio);
    $stmt->bindValue(':codigo', $codigo, PDO::PARAM_INT);
    $stmt->execute();
    $patrimonio = $stmt->fetch();
} catch (PDOException $e) {
    error_log("Erro ao buscar patrimônio para baixa: " . $e->getMessage());
    $erro = "Erro interno ao carregar dados do patrimônio.";
    if (!empty($salaAtual)) {
        header('Location: ../controllers/controller_lista_sala.php?nome_sala=' . urlencode($salaAtual) . '&erro_baixa=' . urlencode($erro));
    } else {
        header('Location: ../controllers/controller_lista_patrimonio.php?erro_baixa=' . urlencode($erro));
    }
    exit;
}

if (!$patrimonio) {
    $erro = "Patrimônio com código " . htmlspecialchars($codigo) . " não encontrado.";
    if (!empty($salaAtual)) {
        // Redirecionamento 3 CORRIGIDO
        header('Location: ../controllers/controller_lista_sala.php?nome_sala=' . urlencode($salaAtual) . '&erro_baixa=' . urlencode($erro));
    } else {
        // Redirecionamento 4 CORRIGIDO
        header('Location: ../controllers/controller_lista_patrimonio.php?erro_baixa=' . urlencode($erro));
    }
    exit;
}

$salaAtual = $patrimonio['nome_sala'];
// ... (Resto do HTML é mantido)
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    </head>
<body>
    <section class="principal">
        <div style="text-align: right; margin-bottom: 20px; font-size: 0.9em;">
            <span style="color: #666;">Usuário: <?= htmlspecialchars($_SESSION['user_nome'] ?? 'N/A') ?></span> |
            <a href="../principal/index.php" class="btn-voltar-index">Voltar para Salas</a>
        </div>
        
        <h1 class="titulo-alerta">⚠️ Confirmação de Baixa/Exclusão</h1>

        <div class="alerta-aviso">
            <p>
                Você está prestes a dar **BAIXA/EXCLUIR PERMANENTEMENTE** o seguinte patrimônio:
            </p>
            <hr>
            <p>
                **Código:** <?= htmlspecialchars($codigo) ?>
            </p>
            <p>
                **Tipo:** <?= htmlspecialchars($patrimonio['tipo']) ?>
            </p>
            <?php if (!empty($patrimonio['marca'])): ?>
                <p>
                    **Marca:** <?= htmlspecialchars($patrimonio['marca']) ?>
                </p>
            <?php endif; ?>

            <p class="mensagem-irreversivel">
                Esta ação é irreversível. Tem certeza de que deseja continuar?
            </p>
        </div>

        <div class="action-buttons-container">
            
            <a href="../controllers/controller_baixa_patrimonio.php?codigo=<?= urlencode($codigo) ?>&sala_atual=<?= urlencode($salaAtual ?? '') ?>" 
               class="btn-deletar">
                SIM, Dar Baixa/Excluir
            </a>

            <a href="../controllers/controller_lista_sala.php?nome_sala=<?= urlencode($salaAtual ?? '') ?>"
               class="btn-voltar-index"
               style="margin-left: 20px;">
                Cancelar e Voltar
            </a>
        </div>
    </section>
</body>
</html>