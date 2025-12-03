<?php
$host = 'localhost';
$dbname = 'sge';
$user = 'root';
$password = 'root'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("<h1>Erro ao conectar com o Banco de Dados:</h1><p>Verifique o arquivo `db_connect.php` e as credenciais.</p>Detalhes: " . $e->getMessage());
}
?>