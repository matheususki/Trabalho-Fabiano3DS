<?php
require_once 'conexao.php';

// Buscar todos os registros ordenados pelo ID decrescente (mais recentes primeiro)
$sql = "SELECT id, nome, mensagem FROM usuarios ORDER BY id DESC";
$result = $conn->query($sql);
$registros = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $registros[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Mensagens</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            overflow: hidden;
        }

        h2 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            margin: 0;
            text-align: center;
            font-weight: 600;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        h2 a {
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 30px;
            transition: background 0.3s;
        }

        h2 a:hover {
            background: rgba(255,255,255,0.3);
        }

        .lista-mensagens {
            padding: 30px;
        }

        .mensagem-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid #667eea;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .mensagem-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .mensagem-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .mensagem-autor {
            font-weight: 700;
            color: #333;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }

        .mensagem-autor::before {
            content: "👤";
            margin-right: 8px;
            font-size: 1.2rem;
        }

        .mensagem-acoes {
            display: flex;
            gap: 10px;
        }

        .btn-editar, .btn-excluir {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-editar {
            color: #667eea;
            background: #eef2ff;
        }

        .btn-editar:hover {
            background: #667eea;
            color: white;
        }

        .btn-excluir {
            color: #dc3545;
            background: #ffeaea;
        }

        .btn-excluir:hover {
            background: #dc3545;
            color: white;
        }

        .mensagem-conteudo {
            color: #444;
            line-height: 1.5;
            word-break: break-word;
            padding: 8px 0;
        }

        .mensagem-vazia {
            text-align: center;
            color: #888;
            padding: 40px 20px;
            font-style: italic;
        }

        /* Modal de edição */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(3px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            visibility: hidden;
            opacity: 0;
            transition: 0.2s;
        }

        .modal-overlay.active {
            visibility: visible;
            opacity: 1;
        }

        .modal {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            overflow: hidden;
            transform: scale(0.9);
            transition: transform 0.2s;
        }

        .active .modal {
            transform: scale(1);
        }

        .modal-header {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-body textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
            background: #f9fafb;
        }

        .modal-body textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .modal-footer {
            padding: 0 25px 25px;
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-cancelar {
            background: #f1f3f5;
            color: #333;
        }

        .btn-cancelar:hover {
            background: #e2e6ea;
        }

        .btn-salvar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-salvar:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 15px 25px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            transform: translateY(100px);
            opacity: 0;
            transition: 0.3s;
            z-index: 2000;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.sucesso {
            background: #28a745;
        }

        .toast.erro {
            background: #dc3545;
        }

        @media (max-width: 600px) {
            h2 {
                flex-direction: column;
                gap: 10px;
                font-size: 1.5rem;
            }
            .mensagem-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>
            📋 Mensagens Recebidas
            <a href="index.html">➕ Novo Cadastro</a>
        </h2>
        <div class="lista-mensagens" id="listaMensagens">
            <?php if (empty($registros)): ?>
                <div class="mensagem-vazia">Nenhuma mensagem cadastrada ainda.</div>
            <?php else: ?>
                <?php foreach ($registros as $reg): ?>
                <div class="mensagem-card" data-id="<?= $reg['id'] ?>">
                    <div class="mensagem-header">
                        <span class="mensagem-autor"><?= htmlspecialchars($reg['nome']) ?></span>
                        <div class="mensagem-acoes">
                            <button class="btn-editar" onclick="abrirModalEdicao(<?= $reg['id'] ?>, '<?= htmlspecialchars(addslashes($reg['mensagem'])) ?>')">✏️ Editar</button>
                            <button class="btn-excluir" onclick="excluirMensagem(<?= $reg['id'] ?>)">🗑️ Excluir</button>
                        </div>
                    </div>
                    <div class="mensagem-conteudo"><?= nl2br(htmlspecialchars($reg['mensagem'])) ?></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div class="modal-overlay" id="modalEdicao">
        <div class="modal">
            <div class="modal-header">✏️ Editar Mensagem</div>
            <div class="modal-body">
                <textarea id="mensagemEdit" placeholder="Digite a nova mensagem..." maxlength="250"></textarea>
                <small style="display: block; margin-top: 8px; color: #666;"><span id="contadorEdit">0</span>/250 caracteres</small>
            </div>
            <div class="modal-footer">
                <button class="btn btn-cancelar" onclick="fecharModal()">Cancelar</button>
                <button class="btn btn-salvar" onclick="salvarEdicao()">Salvar Alterações</button>
            </div>
        </div>
    </div>

    <!-- Toast para feedback -->
    <div class="toast" id="toast"></div>

    <script>
        // Elementos globais
        const modal = document.getElementById('modalEdicao');
        const textareaEdit = document.getElementById('mensagemEdit');
        const contadorEdit = document.getElementById('contadorEdit');
        let idEmEdicao = null;

        // Contador de caracteres no modal
        textareaEdit.addEventListener('input', function() {
            const len = this.value.length;
            contadorEdit.textContent = len;
            if (len > 250) {
                this.value = this.value.substring(0, 250);
                contadorEdit.textContent = 250;
            }
        });

        function abrirModalEdicao(id, mensagemAtual) {
            idEmEdicao = id;
            textareaEdit.value = mensagemAtual;
            contadorEdit.textContent = mensagemAtual.length;
            modal.classList.add('active');
        }

        function fecharModal() {
            modal.classList.remove('active');
            idEmEdicao = null;
        }

        // Fechar modal clicando fora
        modal.addEventListener('click', (e) => {
            if (e.target === modal) fecharModal();
        });

        function mostrarToast(mensagem, tipo = 'sucesso') {
            const toast = document.getElementById('toast');
            toast.textContent = mensagem;
            toast.className = `toast ${tipo} show`;
            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }

        async function salvarEdicao() {
            if (!idEmEdicao) return;
            const novaMensagem = textareaEdit.value.trim();
            if (novaMensagem === '') {
                mostrarToast('A mensagem não pode ficar vazia.', 'erro');
                return;
            }

            const formData = new FormData();
            formData.append('acao', 'editar');
            formData.append('id', idEmEdicao);
            formData.append('mensagem', novaMensagem);

            try {
                const response = await fetch('acoes.php', {
                    method: 'POST',
                    body: formData
                });
                const resultado = await response.json();
                
                if (resultado.status === 'sucesso') {
                    mostrarToast('Mensagem atualizada com sucesso!');
                    // Atualiza o conteúdo na interface
                    const card = document.querySelector(`.mensagem-card[data-id="${idEmEdicao}"] .mensagem-conteudo`);
                    if (card) {
                        card.innerHTML = novaMensagem.replace(/\n/g, '<br>');
                    }
                    fecharModal();
                } else {
                    mostrarToast(resultado.mensagem || 'Erro ao editar', 'erro');
                }
            } catch (error) {
                console.error(error);
                mostrarToast('Erro de conexão com o servidor', 'erro');
            }
        }

        async function excluirMensagem(id) {
            if (!confirm('Tem certeza que deseja excluir esta mensagem? Esta ação não pode ser desfeita.')) {
                return;
            }

            const formData = new FormData();
            formData.append('acao', 'excluir');
            formData.append('id', id);

            try {
                const response = await fetch('acoes.php', {
                    method: 'POST',
                    body: formData
                });
                const resultado = await response.json();

                if (resultado.status === 'sucesso') {
                    mostrarToast('Mensagem excluída com sucesso!');
                    // Remove o card do DOM
                    const card = document.querySelector(`.mensagem-card[data-id="${id}"]`);
                    if (card) {
                        card.remove();
                    }
                    // Se não houver mais cards, mostra mensagem vazia
                    const container = document.getElementById('listaMensagens');
                    if (container.children.length === 0) {
                        container.innerHTML = '<div class="mensagem-vazia">Nenhuma mensagem cadastrada ainda.</div>';
                    }
                } else {
                    mostrarToast(resultado.mensagem || 'Erro ao excluir', 'erro');
                }
            } catch (error) {
                console.error(error);
                mostrarToast('Erro de conexão com o servidor', 'erro');
            }
        }

        // Atualizar lista via AJAX se quiser (opcional, já carregamos com PHP)
        // Mas poderia ter um botão de refresh, não necessário.
    </script>
</body>
</html>