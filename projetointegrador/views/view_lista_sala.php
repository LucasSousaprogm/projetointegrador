<?php
// Inclui o controle de autenticação
require '../login_logout/auth_check.php';
// Inclui a conexão com o banco de dados
require "../principal/db_connect.php"; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Definição das variáveis para evitar o "Undefined variable"
// Estes valores são defaults e devem ser sobrescritos pelo controller_lista_sala.php
$nomeSalaAtual = $_GET['nome_sala'] ?? 'Sala Desconhecida'; 

$avisoBaixa = $_GET['avisoBaixa'] ?? '';
$erroBaixa = $_GET['erroBaixa'] ?? '';
$avisoMovimento = $_GET['avisoMovimento'] ?? '';
$erroMovimento = $_GET['erroMovimento'] ?? '';

// Inicialização para a tabela e filtros
$resultPatrimonios = $resultPatrimonios ?? []; 
$errorMessage = $errorMessage ?? '';

$filtros = [
    'codigo' => $_GET['codigo'] ?? '',
    'tipo' => $_GET['tipo'] ?? ''
];

// NOTE: Este arquivo *não* contém a lógica de banco de dados para buscar os patrimônios.
// Essa lógica deve estar no arquivo controller_lista_sala.php, que deve incluir esta view.
// A lógica do controller deve preencher as variáveis $resultPatrimonios e $errorMessage.
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css"> 
    <title>Patrimônio - Sala: <?= htmlspecialchars($nomeSalaAtual) ?></title>
</head>

<body>
    <section class="principal">
        <div style="text-align: right; margin-bottom: 20px; font-size: 0.9em;">
            <span style="color: #666;">Usuário: <?= htmlspecialchars($_SESSION['username'] ?? 'N/A') ?></span> |
            <a href="../views/view_cria_patrimonio.php" class="btn-novo-patrimonio">Novo Patrimônio</a> |
            <a href="../principal/index.php" class="btn-voltar-index">Voltar para Salas</a> |
            <a href="../login_logout/logout.php" class="btn-deletar">Sair</a>
        </div>
        
        <h1>Patrimônio em: <?= htmlspecialchars($nomeSalaAtual) ?></h1>
        
        <?php if (!empty($avisoBaixa)): ?>
            <div class="alerta-patrimonio-movido" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
                <?= htmlspecialchars($avisoBaixa) ?>
                <span class="fechar" onclick="this.parentElement.style.display='none';">x</span>
            </div>
        <?php endif; ?>

        <?php if (!empty($erroBaixa)): ?>
            <div class="alerta-erro" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                <?= htmlspecialchars($erroBaixa) ?>
                <span class="fechar" onclick="this.parentElement.style.display='none';">x</span>
            </div>
        <?php endif; ?>

        <?php if (!empty($avisoMovimento)): ?>
            <div class="alerta-patrimonio-movido" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
                <?= htmlspecialchars($avisoMovimento) ?>
                <span class="fechar" onclick="this.parentElement.style.display='none';">x</span>
            </div>
        <?php endif; ?>

        <?php if (!empty($erroMovimento)): ?>
            <div class="alerta-erro" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                <?= htmlspecialchars($erroMovimento) ?>
                <span class="fechar" onclick="this.parentElement.style.display='none';">x</span>
            </div>
        <?php endif; ?>

        <form method="GET" action="controller_lista_sala.php" class="filtros">
            <input type="hidden" name="nome_sala" value="<?= htmlspecialchars($nomeSalaAtual) ?>">
            
            <div>
                <label for="codigo">Cód.:</label>
                <input type="number" id="codigo" name="codigo" value="<?= htmlspecialchars($filtros['codigo'] ?? '') ?>">
            </div>
            <div>
                <label for="tipo">Tipo:</label>
                <input type="text" id="tipo" name="tipo" value="<?= htmlspecialchars($filtros['tipo'] ?? '') ?>">
            </div>
            <button type="submit" class="btn-filtrar">Filtrar</button>
            <a href="controller_lista_sala.php?nome_sala=<?= urlencode($nomeSalaAtual) ?>" class="btn-limpar">Limpar Filtros</a>
        </form>
        
        <?php if ($errorMessage): ?>
            <div class="alerta-erro">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php else: ?>
        <table class="principal-tabela">
            <thead>
                <tr>
                    <th>Cód.</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Ano</th>
                    <th>Qtd.</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($resultPatrimonios)) { 
                    foreach ($resultPatrimonios as $patrimonio) { ?>
                        <tr>
                            <td data-label="Cód."><?= htmlspecialchars($patrimonio['codigo']) ?></td>
                            <td data-label="Tipo" class="coluna-tipo"><?= htmlspecialchars($patrimonio['tipo']) ?></td>
                            <td data-label="Marca"><?= htmlspecialchars($patrimonio['marca']) ?></td>
                            <td data-label="Ano"><?= htmlspecialchars($patrimonio['ano']) ?></td>
                            <td data-label="Quantidade"><?= htmlspecialchars($patrimonio['quantidade']) ?></td>
                            <td class="coluna-acoes">
                                <a href="../views/view_edita_patrimonio.php?codigo=<?= $patrimonio['codigo'] ?>" class="btn-editar">Editar</a>
                                <a href="controller_baixa_patrimonio.php?codigo=<?= $patrimonio['codigo'] ?>&sala_atual=<?= urlencode($nomeSalaAtual) ?>" class="btn-deletar" onclick="return confirm('Tem certeza que deseja dar Baixa (excluir) o patrimônio <?= $patrimonio['codigo'] ?> da sala?')">Baixar</a>
                                
                                <a href="../views/view_move_patrimonio.php?codigo=<?= $patrimonio['codigo'] ?>&sala_origem=<?= urlencode($nomeSalaAtual) ?>" class="btn-mover">Mover</a>
                            </td>
                        </tr>
                <?php
                    }
                } else { ?>
                    <tr>
                        <td colspan="6">Nenhum patrimônio encontrado nesta sala com os filtros aplicados.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php endif; ?>
    </section>
</body>

</html>