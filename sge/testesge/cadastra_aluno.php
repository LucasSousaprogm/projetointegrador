<?php

require "../db_conector.php"; 

$nome = $_POST['nome'];
$razao = $_POST['razao'];

$sqlinsert="INSERT INTO sala(nome, razao) VALUES('$nome', '$razao');";
$pdo->exec($sqlinsert);