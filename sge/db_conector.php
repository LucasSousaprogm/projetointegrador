<?php

$usuario='root';
$senha='root';
$nome_db='sge';


try{
    $pdo= new PDO ("mysql: host:127.0.0.1; port=3306; dbname=$nome_db","$usuario", "$senha");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    echo "<h1>Conexão Estabelecida.</h1>";
}

catch (PDOException $e) {
    echo "<h1 style='color: red;'>Error: </h1>" . $e->getMessage();
}