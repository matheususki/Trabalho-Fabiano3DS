<?php
header('Content-Type: application/json');
require_once 'conexao.php';

try {
    // ... validações e inserção usando $conn ...
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>