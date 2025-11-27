<?php
require '../login_logout/auth_check.php';
require "../principal/db_connect.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/view_cria_patrimonio.php');
    exit;
}

$codigo = $_POST['codigo'] ?? null;
$tipo = trim($_POST['tipo'] ?? '');
$marca = trim($_POST['marca'] ?? '');
$ano = $_POST['ano'] ?? null;
$quantidade = $_POST['quantidade'] ?? null;
$sala_de_origem = trim($_POST['sala_de_origem'] ?? '');
$nome_sala = trim($_POST['nome_sala'] ?? '');

if (empty($codigo) || empty($tipo) || empty($quantidade) || empty($sala_de_origem) || empty($nome_sala)) {
    $erro = "Erro: Dados obrigatórios incompletos.";
    header('Location: ../views/view_cria_patrimonio.php?erro=' . urlencode($erro));
    exit;
}

$queryInsert = "INSERT INTO patrimonio 
                (codigo, tipo, marca, ano, quantidade, sala_de_origem, nome_sala) 
                VALUES 
                (:codigo, :tipo, :marca, :ano, :quantidade, :sala_de_origem, :nome_sala)";

try {
    $stmt = $pdo->prepare($queryInsert);

    $stmt->bindValue(':codigo', $codigo, PDO::PARAM_INT);
    $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindValue(':marca', $marca, PDO::PARAM_STR);
    $stmt->bindValue(':ano', is_numeric($ano) ? $ano : null, PDO::PARAM_INT); 
    $stmt->bindValue(':quantidade', $quantidade, PDO::PARAM_INT);
    $stmt->bindValue(':sala_de_origem', $sala_de_origem, PDO::PARAM_STR);
    $stmt->bindValue(':nome_sala', $nome_sala, PDO::PARAM_STR);
    
    $stmt->execute();
    
    header('Location: ../views/view_cria_patrimonio.php?sucesso=' . urlencode($codigo));
    exit;
    
} catch (PDOException $e) {
    if ($e->getCode() === '23000') { 
        $erro = "Erro: Já existe um patrimônio com o código " . htmlspecialchars($codigo) . ".";
    } else {
        error_log("Erro ao criar patrimônio: " . $e->getMessage());
        $erro = "Erro interno ao tentar cadastrar patrimônio.";
    }
    header('Location: ../views/view_cria_patrimonio.php?erro=' . urlencode($erro));
    exit;
}
?>