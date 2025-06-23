<?php
$servername = "localhost";   
$username = "root";          
$password = "";              
$dbname = "crud-clientes";   

$conn = new mysqli($servername, $username, $password, $dbname, 3307);

if ($conn->connect_error) {
    die("falha ao se conectar com o banco de dados: " . $conn->connect_error);
}

$conn->set_charset("utf8");

$sqlCreateTable = "
    CREATE TABLE IF NOT EXISTS tb_clientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        telefone VARCHAR(20),
        endereco VARCHAR(255)
    )
";

if (!$conn->query($sqlCreateTable)) {
     
    die("Erro ao criar tabela: " . $conn->error);
}
?>