<?php
require '../login_logout/auth_check.php';
require "../principal/db_connect.php";

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../views/view_login.php'); // Redireciona para o login
    exit;
}


$nomeSalaAtual = $_GET['nome_sala'] ?? null;
$avisoBaixa = $_GET['aviso_baixa'] ?? null;
$erroBaixa = $_GET['erro_baixa'] ?? null;
$avisoMovimento = $_GET['aviso_movimento'] ?? null;
$erroMovimento = $_GET['erro_movimento'] ?? null;

if (empty($nomeSalaAtual)) {
    header('Location: index.php');
    exit;
}

$filtros = ['nome_sala' => $nomeSalaAtual];
$whereClauses = ["nome_sala = :nome_sala"];
$params = [':nome_sala' => $nomeSalaAtual];
$errorMessage = null; 
$resultPatrimonios = [];

if (!empty($_GET['codigo'])) {
    $codigo = trim($_GET['codigo']);
    if (is_numeric($codigo)) {
        $whereClauses[] = "codigo = :codigo";
        $params[':codigo'] = $codigo;
        $filtros['codigo'] = $codigo;
    }
}

if (!empty($_GET['tipo'])) {
    $tipo = trim($_GET['tipo']);
    $whereClauses[] = "tipo LIKE :tipo";
    $params[':tipo'] = "%" . $tipo . "%";
    $filtros['tipo'] = $tipo;
}

$selecionaPatrimonio = "SELECT * FROM patrimonio";

if (!empty($whereClauses)) {
    $selecionaPatrimonio .= " WHERE " . implode(" AND ", $whereClauses);
}

$selecionaPatrimonio .= " ORDER BY codigo ASC";

try {
    $stmt = $pdo->prepare($selecionaPatrimonio);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $resultPatrimonios = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $errorMessage = "Erro ao buscar patrimônio: " . $e->getMessage();
}


require '../views/view_lista_sala.php';

?>