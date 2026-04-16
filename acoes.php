<?php
header('Content-Type: application/json');
require_once 'conexao.php';

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

try {
    switch ($acao) {
        case 'listar':
            // Opcional: pode ser usado para recarregar via AJAX
            $sql = "SELECT id, nome, mensagem FROM usuarios ORDER BY id DESC";
            $result = $conn->query($sql);
            $dados = [];
            while ($row = $result->fetch_assoc()) {
                $dados[] = $row;
            }
            echo json_encode(['status' => 'sucesso', 'dados' => $dados]);
            break;

        case 'editar':
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $mensagem = trim($_POST['mensagem'] ?? '');
            
            if (!$id) {
                throw new Exception('ID inválido.');
            }
            if ($mensagem === '') {
                throw new Exception('A mensagem não pode estar vazia.');
            }
            if (strlen($mensagem) > 250) {
                throw new Exception('A mensagem excede 250 caracteres.');
            }

            $stmt = $conn->prepare("UPDATE usuarios SET mensagem = ? WHERE id = ?");
            $stmt->bind_param("si", $mensagem, $id);
            if (!$stmt->execute()) {
                throw new Exception('Erro ao atualizar: ' . $stmt->error);
            }
            if ($stmt->affected_rows === 0) {
                // Pode ser que o ID não exista
                throw new Exception('Nenhum registro alterado. Verifique o ID.');
            }
            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Mensagem atualizada.']);
            $stmt->close();
            break;

        case 'excluir':
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) {
                throw new Exception('ID inválido.');
            }

            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                throw new Exception('Erro ao excluir: ' . $stmt->error);
            }
            if ($stmt->affected_rows === 0) {
                throw new Exception('Registro não encontrado.');
            }
            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Registro excluído.']);
            $stmt->close();
            break;

        default:
            throw new Exception('Ação não reconhecida.');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
} finally {
    if (isset($conn)) $conn->close();
}
?>