<?php
header('Content-Type: application/json');


$servername = "localhost";
$username = "root";
$password = "&tec77@info!";  
$dbname = "bd_nm";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão: " . $conn->connect_error);
    }
    
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');
    
    
    if (empty($nome) || empty($email) || empty($senha) || empty($mensagem)) {
        throw new Exception("Todos os campos são obrigatórios");
    }
    
    
    if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/", $nome)) {
        throw new Exception("O nome deve conter apenas letras e espaços.");
    }
    
    
    if (strlen($mensagem) > 250) {
        throw new Exception("A mensagem não pode ter mais de 250 caracteres");
    }
    
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Email inválido");
    }
    
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, mensagem) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha_hash, $mensagem);
    
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'sucesso',
            'mensagem' => 'Dados salvos com sucesso!'
        ]);
    } else {
        throw new Exception("Erro ao salvar os dados");
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'erro',
        'mensagem' => $e->getMessage()
    ]);
}
?>