<?php
require '../login_logout/auth_check.php';
require "../principal/db_connect.php";

$filtros = [];
$whereClauses = [];
$params = [];
$avisoBaixa = $_GET['aviso_baixa'] ?? null;
$erroBaixa = $_GET['erro_baixa'] ?? null;
$avisoEdicao = $_GET['aviso_edicao'] ?? null;
$erroEdicao = $_GET['erro_edicao'] ?? null;
$errorMessage = null; 

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

if (!empty($_GET['sala_origem'])) {
    $sala_origem = trim($_GET['sala_origem']);
    $whereClauses[] = "sala_de_origem LIKE :sala_origem";
    $params[':sala_origem'] = "%" . $sala_origem . "%";
    $filtros['sala_origem'] = $sala_origem;
}

if (!empty($_GET['nome_sala'])) {
    $nome_sala = trim($_GET['nome_sala']);
    $whereClauses[] = "nome_sala LIKE :nome_sala";
    $params[':nome_sala'] = "%" . $nome_sala . "%";
    $filtros['nome_sala'] = $nome_sala;
}

if (!empty($_GET['marca'])) {
    $marca = trim($_GET['marca']);
    $whereClauses[] = "marca LIKE :marca";
    $params[':marca'] = "%" . $marca . "%";
    $filtros['marca'] = $marca;
}

if (!empty($_GET['ano'])) {
    $ano = trim($_GET['ano']);
    if (is_numeric($ano)) {
        $whereClauses[] = "ano = :ano";
        $params[':ano'] = $ano;
        $filtros['ano'] = $ano;
    }
}


$selecionaPatrimonio = "SELECT * FROM patrimonio";

if (!empty($whereClauses)) {
    $selecionaPatrimonio .= " WHERE " . implode(' AND ', $whereClauses);
}

$selecionaPatrimonio .= " ORDER BY codigo ASC";

$resultPatrimonios = [];

try {
    $stmt = $pdo->prepare($selecionaPatrimonio);

    foreach ($params as $key => &$value) {
        $stmt->bindParam($key, $value);
    }

    $stmt->execute();
    $resultPatrimonios = $stmt->fetchAll();
} catch (PDOException $e) {
    $errorMessage = "<div class=\"alerta-erro\">Erro ao carregar lista de patrimônios: " . $e->getMessage() . "</div>";
    error_log("Erro ao listar patrimônio: " . $e->getMessage());
}

require '../views/view_lista_patrimonio.php';
?>