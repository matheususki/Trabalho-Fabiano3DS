<?php
$servername = "localhost";
$username = "root";
$password = "&tec77@info!";
$dbname = "bd_nm";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se houve erro
if ($conn->connect_error) {
    die(json_encode(["status" => "erro", "mensagem" => "Falha na conexão: " . $conn->connect_error]));
}

// Define charset UTF-8 para evitar problemas com acentos
$conn->set_charset("utf8mb4");
?>