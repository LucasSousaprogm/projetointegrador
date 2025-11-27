<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - Sistema</title>
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>

    <div class="login-container">
        
        <h2>Cadastro de Novo Usuário</h2>
        
        <?php
        // Exibe mensagem de erro
        if (isset($_GET['erro'])) {
            $erro = htmlspecialchars(urldecode($_GET['erro']));
            echo '<p class="mensagem-erro">🚫 ' . $erro . '</p>';
        }
        
        // Exibe mensagem de sucesso
        if (isset($_GET['sucesso'])) {
            $cpf_sucesso = htmlspecialchars(urldecode($_GET['sucesso']));
            echo '<p class="mensagem-sucesso">✅ Usuário com CPF **' . $cpf_sucesso . '** cadastrado com sucesso! Você já pode fazer login.</p>';
            // Adicionando um estilo simples para sucesso, caso não tenha no style_login.css
            echo '<style>.mensagem-sucesso { background-color: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; padding: 10px; border-radius: 4px; margin-bottom: 20px; }</style>';
        }
        ?>

        <form action="../controllers/controller_cria_funcionario.php" method="POST">
            
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required 
                       placeholder="Seu nome" maxlength="100">
            </div>

            <div class="form-group">
                <label for="cpf">CPF (Será seu Login):</label>
                <input type="text" id="cpf" name="cpf" required 
                       placeholder="Apenas números (11 dígitos)" 
                       pattern="[0-9]{11}" 
                       title="Digite exatamente 11 números para o CPF"
                       maxlength="11">
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required 
                       placeholder="Crie uma senha" minlength="6">
            </div>

            <button type="submit" class="btn-login">Cadastrar</button>
            
        </form>

        <p class="cadastro-link">
            Já tem cadastro? <a href="view_login.php">Fazer Login</a>.
        </p>
        
    </div>

</body>
</html>