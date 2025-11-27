<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gerenciamento de Patrimônio</title>
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>

    <div class="login-container">
        
        <h2>Acesso ao Sistema</h2>
        
        <?php
        session_start(); // Inicia a sessão para checar as mensagens.

        if (isset($_GET['erro'])) {
            $erro = htmlspecialchars(urldecode($_GET['erro']));
            echo '<p class="mensagem-erro">🚫 ' . $erro . '</p>';
        }
        
        if (isset($_GET['sucesso_cadastro'])) {
            $cpf = htmlspecialchars(urldecode($_GET['sucesso_cadastro']));
            echo '<p class="mensagem-sucesso">✅ Cadastro realizado! Faça login com o CPF: ' . $cpf . '</p>';
            // Adicionando um estilo simples para sucesso
            echo '<style>.mensagem-sucesso { background-color: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; padding: 10px; border-radius: 4px; margin-bottom: 20px; }</style>';
        }
        ?>

        <form action="../login_logout/controller_login.php" method="POST">
            
            <div class="form-group">
                <label for="cpf">CPF (Seu Login):</label>
                <input type="text" id="cpf" name="cpf" required 
                       placeholder="Apenas números (11 dígitos)" 
                       pattern="[0-9]{11}" 
                       title="Digite exatamente 11 números para o CPF"
                       maxlength="11">
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required 
                       placeholder="Sua senha secreta">
            </div>

            <button type="submit" class="btn-login">Entrar</button>
            
        </form>

        <p class="link-cadastro">
            Não tem cadastro? 
            <a href="view_cria_usuario.php">Cadastre-se aqui</a>
        </p>

    </div>

</body>
</html>