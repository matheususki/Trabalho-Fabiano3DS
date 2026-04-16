markdown
# Trabalho-Fabiano3DS
Trabalho Escolar Feito por Nicolas Anderson e Matheus Suski

================================================================================
                MANUAL COMPLETO DO PROJETO: SISTEMA DE CADASTRO
                  COM PAINEL DE GERENCIAMENTO DE MENSAGENS
                          PHP + MySQL + HTML/CSS/JS
================================================================================

Este manual fornece instruções detalhadas para instalar, configurar, utilizar e personalizar o sistema de cadastro de usuários com painel administrativo para visualização, edição e exclusão de mensagens. O projeto consiste em um formulário web que valida e armazena dados em um banco de dados MySQL, complementado por uma interface moderna para gerenciar os registros.

ÍNDICE
-------
1. INTRODUÇÃO
2. REQUISITOS DO SISTEMA
3. ESTRUTURA DE ARQUIVOS
4. CONFIGURAÇÃO DO BANCO DE DADOS
   4.1. Criar o Banco de Dados e a Tabela
   4.2. Criar um Usuário Específico para o Projeto (Recomendado)
   4.3. Verificar se o MySQL está Rodando
5. CONFIGURAÇÃO DO PROJETO
   5.1. Ajustar Credenciais no PHP (Arquivo conexao.php)
   5.2. Colocar os Arquivos no Servidor
6. TESTE DE CONEXÃO
7. UTILIZAÇÃO DO SISTEMA
   7.1. Formulário de Cadastro (index.html)
   7.2. Painel de Gerenciamento (listar.php)
8. FUNCIONALIDADES DETALHADAS (COM EXEMPLOS DE CÓDIGO)
   8.1. Validação no Front-end (HTML/JS)
   8.2. Validação no Back-end (PHP)
   8.3. Contador de Caracteres para Mensagem
   8.4. Painel Administrativo (listar.php + acoes.php)
       8.4.1. Listagem de Registros
       8.4.2. Edição de Mensagem (Modal)
       8.4.3. Exclusão de Registro
   8.5. Estilização CSS Responsiva
9. SOLUÇÃO DE PROBLEMAS COMUNS (TROUBLESHOOTING)
   9.1. Erro de Acesso Negado ao MySQL (Access Denied)
   9.2. Erro "No such file or directory" ao Conectar
   9.3. Erro "Failed opening required 'conexao.php'"
   9.4. Página 404 Not Found
   9.5. PHP não é Processado (Mostra o código fonte)
   9.6. Mensagem de Sucesso mas Dados Não Aparecem no Banco
   9.7. Botões de Editar/Excluir não Funcionam
10. SEGURANÇA IMPLEMENTADA
11. PERSONALIZAÇÃO AVANÇADA
    11.1. Alterar Cores e Estilo
    11.2. Adicionar Novos Campos
    11.3. Alterar Limites de Caracteres
    11.4. Traduzir Mensagens
12. REFERÊNCIAS E SUPORTE

================================================================================
1. INTRODUÇÃO
================================================================================
Este sistema foi desenvolvido para ser uma base de cadastro simples, porém completa, com validações tanto no lado do cliente quanto no servidor. Ele permite coletar nome, email, senha e uma mensagem (limitada a 250 caracteres), e armazenar essas informações de forma segura em um banco MySQL. Além disso, agora conta com um painel administrativo onde é possível visualizar todas as mensagens enviadas, editar o conteúdo de cada uma e excluir registros indesejados.

A interface é moderna, responsiva e oferece feedback claro ao usuário, utilizando modais para edição e confirmações para exclusão.

================================================================================
2. REQUISITOS DO SISTEMA
================================================================================
Para executar este projeto, você precisa ter instalado em seu ambiente de desenvolvimento ou produção:

- Servidor Web: Apache (recomendado), Nginx ou IIS com suporte a PHP.
- PHP: versão 7.4 ou superior (recomenda-se PHP 8.x).
- MySQL: versão 5.7 ou superior (ou MariaDB 10.3+).
- Navegador: qualquer navegador moderno (Chrome, Firefox, Edge, Safari).
- (Opcional) Ferramentas de administração: MySQL Workbench, phpMyAdmin ou acesso ao terminal.

Se estiver usando um pacote como XAMPP, WAMP ou MAMP, ele já inclui Apache, PHP e MySQL.

================================================================================
3. ESTRUTURA DE ARQUIVOS
================================================================================
Os arquivos abaixo devem estar dentro de uma mesma pasta no diretório público do servidor web (ex: htdocs/Ms-3DS/).

| Arquivo              | Descrição                                                                 |
|----------------------|---------------------------------------------------------------------------|
| index.html           | Página principal com o formulário de cadastro, CSS e JavaScript.           |
| processar.php        | Script PHP que recebe os dados do formulário, valida, aplica hash na senha e insere no MySQL. |
| listar.php           | Painel administrativo que exibe todos os registros em cards, com opções de editar e excluir. |
| acoes.php            | Endpoint PHP que processa requisições AJAX para listar, editar e excluir registros. |
| conexao.php          | Arquivo centralizado com as credenciais e a conexão com o banco de dados.  |
| bd_nm_usuarios.sql   | Dump da estrutura da tabela `usuarios` (opcional para importação).         |
| README.txt           | Este manual.                                                              |

Certifique-se de que todos os arquivos tenham permissões de leitura e execução adequadas no servidor.

================================================================================
4. CONFIGURAÇÃO DO BANCO DE DADOS
================================================================================
Antes de usar o sistema, é necessário criar o banco de dados e a tabela onde os dados serão armazenados.

4.1. Criar o Banco de Dados e a Tabela
----------------------------------------
Acesse o MySQL Workbench, phpMyAdmin ou a linha de comando e execute os seguintes comandos SQL:

```sql
-- Cria o banco de dados (se não existir)
CREATE DATABASE IF NOT EXISTS bd_nm;
USE bd_nm;

-- Cria a tabela 'usuarios'
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome TEXT NOT NULL,
    email TEXT NOT NULL,
    senha TEXT NOT NULL,
    mensagem TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Opcional: você pode adicionar índices para melhorar a performance
CREATE INDEX idx_email ON usuarios(email);
Explicação dos campos:

id: chave primária, auto incrementada.

nome: armazena o nome do usuário (texto puro).

email: armazena o e-mail (validado no código).

senha: armazena o hash da senha gerado pelo PHP.

mensagem: armazena a mensagem com limite de 250 caracteres.

4.2. Criar um Usuário Específico para o Projeto (Recomendado)

Em vez de usar o usuário root, é mais seguro criar um usuário com permissões apenas para este banco. No MySQL Workbench ou linha de comando:

sql
-- Cria o usuário 'appuser' com senha '123456'
CREATE USER 'appuser'@'localhost' IDENTIFIED BY '123456';

-- Concede todos os privilégios no banco bd_nm para este usuário
GRANT ALL PRIVILEGES ON bd_nm.* TO 'appuser'@'localhost';

-- Atualiza as permissões
FLUSH PRIVILEGES;
Se você estiver usando um servidor remoto, substitua 'localhost' pelo endereço IP de onde as conexões virão.

4.3. Verificar se o MySQL está Rodando

No Windows: abra o "Serviços" (services.msc) e verifique se o serviço "MySQL" ou "MariaDB" está em execução.

No XAMPP: abra o painel de controle e confira se o MySQL está com status "Running".

No Linux/Mac: execute sudo systemctl status mysql ou sudo service mysql status.

Se o MySQL não estiver rodando, inicie-o. No XAMPP, clique em "Start" ao lado de MySQL. Em outros ambientes, use o comando apropriado.

================================================================================
5. CONFIGURAÇÃO DO PROJETO
================================================================================
5.1. Ajustar Credenciais no PHP (Arquivo conexao.php)

Todas as credenciais de conexão com o banco de dados estão centralizadas no arquivo conexao.php. Abra este arquivo e localize as seguintes linhas:

php
$servername = "localhost";
$username = "root";
$password = "&tec77@info!";   // Altere para sua senha, se houver
$dbname = "bd_nm";
Substitua pelos valores correspondentes ao seu ambiente:

$servername: pode ser "localhost", "127.0.0.1" ou o endereço do servidor MySQL.

$username: "root" ou o nome do usuário que criou (ex: "appuser").

$password: a senha do usuário. Se não houver senha, deixe como string vazia "".

$dbname: o nome do banco criado (ex: "bd_nm").

Os demais arquivos (processar.php, listar.php, acoes.php) já incluem conexao.php automaticamente, portanto você só precisa ajustar as credenciais uma única vez.

5.2. Colocar os Arquivos no Servidor

Para XAMPP: copie todos os arquivos para C:\xampp\htdocs\Ms-3DS\ (ou outra pasta de sua preferência).

Para WAMP: copie para C:\wamp\www\Ms-3DS\.

Para MAMP (Mac): copie para /Applications/MAMP/htdocs/Ms-3DS/.

Para servidor online: utilize um cliente FTP (FileZilla, etc.) para enviar os arquivos para o diretório público (geralmente public_html ou www).

Após copiar, o sistema estará acessível via navegador através de um URL como http://localhost/Ms-3DS/index.html.

================================================================================
6. TESTE DE CONEXÃO
================================================================================
Para garantir que o PHP consegue se comunicar com o MySQL, crie um arquivo teste_conexao.php com o seguinte conteúdo:

php
<?php
require_once 'conexao.php';

if ($conn->connect_error) {
    die("ERRO: " . $conn->connect_error);
} else {
    echo "SUCESSO: Conectado ao banco de dados!";
}
$conn->close();
?>
Acesse este arquivo pelo navegador, ex: http://localhost/Ms-3DS/teste_conexao.php.

Se aparecer "SUCESSO: Conectado ao banco de dados!", a configuração está correta.

Se aparecer um erro, leia a mensagem e verifique as credenciais e se o MySQL está rodando.

================================================================================
7. UTILIZAÇÃO DO SISTEMA
================================================================================
7.1. Formulário de Cadastro (index.html)

Abra o navegador e digite: http://localhost/Ms-3DS/index.html

Preencha todos os campos:

Nome: apenas letras e espaços. Ex: "João Silva". Números e símbolos serão bloqueados no front-end e também validados no back-end.

Email: deve ser um endereço válido, como "usuario@exemplo.com".

Senha: qualquer texto. Será armazenada com hash (não é possível visualizar a senha original no banco).

Mensagem: até 250 caracteres. Um contador abaixo mostra quantos foram digitados.

Clique em "Enviar".

Aguarde o feedback:

Sucesso: uma mensagem verde "Dados salvos com sucesso!" será exibida e o formulário será resetado.

Erro: uma mensagem vermelha indicará o problema (ex: "O nome deve conter apenas letras e espaços.").

Para acessar o painel de mensagens, clique no botão "Ver Mensagens Cadastradas" abaixo do formulário, ou acesse diretamente http://localhost/Ms-3DS/listar.php.

7.2. Painel de Gerenciamento (listar.php)

Ao acessar listar.php, você verá uma lista de cards contendo:

Nome do usuário

Mensagem enviada

Botões "Editar" e "Excluir"

Funcionalidades disponíveis:

Editar: Abre um modal onde você pode modificar o texto da mensagem. Após salvar, o card é atualizado instantaneamente sem recarregar a página.

Excluir: Remove permanentemente o registro após confirmação do usuário.

Novo Cadastro: Link no cabeçalho para retornar ao formulário de cadastro.

Todas as ações são processadas via AJAX, proporcionando uma experiência fluida e responsiva.

================================================================================
8. FUNCIONALIDADES DETALHADAS (COM EXEMPLOS DE CÓDIGO)
================================================================================
8.1. Validação no Front-end (HTML/JS)

O campo "nome" utiliza o atributo HTML5 pattern e uma validação em JavaScript para impedir números ou caracteres especiais.

html
<input type="text" id="nome" name="nome" required pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s]+" title="Apenas letras e espaços">
Além disso, no evento submit do formulário, há uma verificação adicional:

javascript
const nome = document.getElementById('nome').value;
const regexLetras = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;
if (!regexLetras.test(nome)) {
    alert('O nome deve conter apenas letras e espaços.');
    return;
}
8.2. Validação no Back-end (PHP)

O arquivo processar.php valida novamente todos os dados para garantir segurança:

php
if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/", $nome)) {
    throw new Exception("O nome deve conter apenas letras e espaços.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Email inválido");
}
if (strlen($mensagem) > 250) {
    throw new Exception("A mensagem não pode ter mais de 250 caracteres");
}
A senha é transformada em hash antes de ser inserida:

php
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
8.3. Contador de Caracteres para Mensagem

No JavaScript, um evento 'input' atualiza um contador e impede que o usuário digite mais de 250 caracteres:

javascript
mensagemField.addEventListener('input', function() {
    const caracteres = this.value.length;
    contador.textContent = `${caracteres}/250 caracteres`;
    if (caracteres > 250) {
        this.value = this.value.substring(0, 250);
        contador.textContent = `250/250 caracteres`;
    }
});
8.4. Painel Administrativo (listar.php + acoes.php)

8.4.1. Listagem de Registros
No listar.php, os dados são carregados inicialmente via PHP. O arquivo conexao.php é incluído e uma consulta SELECT busca todos os registros ordenados por ID decrescente.

php
$sql = "SELECT id, nome, mensagem FROM usuarios ORDER BY id DESC";
$result = $conn->query($sql);
Cada registro é exibido como um card com os dados e botões de ação.

8.4.2. Edição de Mensagem (Modal)
Ao clicar em "Editar", uma função JavaScript abre um modal contendo um <textarea> com o texto atual. O ID do registro é armazenado em uma variável global. Ao salvar, uma requisição AJAX é enviada para acoes.php com acao=editar.

Trecho do JavaScript:

javascript
async function salvarEdicao() {
    const formData = new FormData();
    formData.append('acao', 'editar');
    formData.append('id', idEmEdicao);
    formData.append('mensagem', novaMensagem);

    const response = await fetch('acoes.php', { method: 'POST', body: formData });
    // Atualiza o card e exibe toast de sucesso
}
No acoes.php, a ação 'editar' valida e atualiza o registro no banco:

php
$stmt = $conn->prepare("UPDATE usuarios SET mensagem = ? WHERE id = ?");
$stmt->bind_param("si", $mensagem, $id);
$stmt->execute();
8.4.3. Exclusão de Registro
A exclusão segue fluxo semelhante: confirmação via confirm() e requisição AJAX para acoes.php com acao=excluir. Após sucesso, o card é removido do DOM.

php
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
8.5. Estilização CSS Responsiva

O CSS utiliza gradiente, sombras, bordas arredondadas e transições. Exemplo do gradiente principal:

css
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
@media (max-width: 600px) {
    .container {
        border-radius: 15px;
    }
}
O painel de mensagens também utiliza cards estilizados, modal de edição com fundo blur e toasts de notificação.

================================================================================
9. SOLUÇÃO DE PROBLEMAS COMUNS (TROUBLESHOOTING)
================================================================================
9.1. Erro de Acesso Negado ao MySQL (Access Denied)

Mensagem: "Access denied for user 'root'@'localhost' (using password: YES)" ou "using password: NO".
Causa: credenciais incorretas no arquivo conexao.php.
Soluções:

Verifique se a senha está correta. Se você usa MySQL Workbench sem senha, deixe $password = "";.

Se mesmo assim não funcionar, crie um novo usuário (conforme item 4.2) e use essas credenciais.

Tente usar "127.0.0.1" em vez de "localhost" no $servername.

9.2. Erro "No such file or directory" ao Conectar

Mensagem: "No such file or directory" em ambiente Windows.
Causa: O MySQL pode estar usando um socket diferente ou o host "localhost" não está resolvido corretamente.
Solução:

Substitua "localhost" por "127.0.0.1" no $servername.

Verifique se o serviço MySQL está em execução.

No php.ini, verifique se a extensão mysqli está habilitada (remova o ponto e vírgula antes de extension=mysqli).

9.3. Erro "Failed opening required 'conexao.php'"

Mensagem: Warning: require_once(conexao.php): Failed to open stream: No such file or directory...
Causa: O arquivo conexao.php não está no mesmo diretório que o script que tenta incluí-lo.
Soluções:

Verifique se o arquivo conexao.php foi criado na pasta correta (ex: C:\xampp\htdocs\Ms-3DS\conexao.php).

Certifique-se de que o nome está exatamente igual (maiúsculas/minúsculas).

No arquivo que dá erro, substitua require_once 'conexao.php'; por require_once __DIR__ . '/conexao.php'; para usar caminho absoluto.

9.4. Página 404 Not Found

Causa: o arquivo não está no local correto dentro do servidor web.
Solução:

Verifique se o arquivo index.html ou listar.php está dentro da pasta do servidor (ex: htdocs/Ms-3DS).

Acesse com o caminho correto: http://localhost/Ms-3DS/index.html.

Verifique se o Apache (ou servidor web) está rodando.

9.5. PHP não é Processado (Mostra o código fonte)

Causa: O servidor não está configurado para executar PHP, ou o arquivo não tem extensão .php.
Solução:

Certifique-se de que os arquivos processar.php, listar.php, acoes.php e conexao.php têm extensão .php.

Verifique se o Apache tem o módulo PHP carregado.

No XAMPP, inicie o Apache e o PHP deve estar ativo por padrão.

9.6. Mensagem de Sucesso mas Dados Não Aparecem no Banco

Causa: Possível erro na inserção que não foi capturado, ou banco/tabela incorretos.
Solução:

Verifique os logs do PHP (em C:\xampp\php\logs\php_error_log) ou ative o display_errors.

Execute manualmente a query de inserção no MySQL para verificar se a tabela existe e tem a estrutura correta.

Confira se o banco de dados selecionado no PHP é o mesmo onde a tabela foi criada.

9.7. Botões de Editar/Excluir não Funcionam

Causa: Erro no console do navegador (JavaScript) ou acoes.php não está respondendo.
Solução:

Abra as Ferramentas do Desenvolvedor (F12) e veja a aba "Console" para erros JS.

Verifique a aba "Network" para ver se as requisições para acoes.php estão sendo enviadas e qual a resposta.

Certifique-se de que acoes.php está acessível e retornando JSON válido.

================================================================================
10. SEGURANÇA IMPLEMENTADA
================================================================================
O projeto adota várias práticas de segurança:

Prepared Statements: Usados no mysqli para evitar SQL Injection.

php
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, mensagem) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $email, $senha_hash, $mensagem);
Hash de Senha: Utiliza password_hash() com algoritmo BCRYPT, garantindo que a senha não seja armazenada em texto puro.

Validação de Dados: Tanto no front-end quanto no back-end, garantindo que entradas maliciosas sejam rejeitadas.

Controle de Erros: As mensagens de erro exibidas ao usuário são genéricas (não revelam detalhes internos do banco). Exceções são capturadas e retornadas como JSON.

Cabeçalhos: O arquivo PHP define header('Content-Type: application/json'); para indicar que a resposta é JSON.

Sanitização de Saída: No painel administrativo, os dados exibidos passam por htmlspecialchars() para evitar XSS.

================================================================================
11. PERSONALIZAÇÃO AVANÇADA
================================================================================
11.1. Alterar Cores e Estilo

Edite o CSS dentro das tags <style> em index.html e listar.php. As cores principais são:

Gradiente de fundo: linear-gradient(135deg, #667eea 0%, #764ba2 100%)

Cor de destaque: #667eea
Para alterar, substitua os valores hexadecimais conforme desejado.

11.2. Adicionar Novos Campos

Para adicionar um campo como "telefone":

Altere a tabela no MySQL: ALTER TABLE usuarios ADD telefone VARCHAR(20);

Em index.html, adicione o campo dentro do <form>.

Em processar.php, capture o valor: $telefone = trim($_POST['telefone'] ?? '');

Valide se necessário e adicione no INSERT e no bind_param.

(Opcional) Exiba o telefone no painel listar.php.

11.3. Alterar Limites de Caracteres

Para aumentar o limite da mensagem para 500 caracteres:

No HTML (index.html): maxlength="500"

No JavaScript: ajuste a condição if (caracteres > 500)

No PHP (processar.php e acoes.php): if (strlen($mensagem) > 500) ...

No painel (listar.php), ajuste o contador do modal.

11.4. Traduzir Mensagens

Todas as mensagens de erro e sucesso estão em português. Para alterar, edite os textos em:

HTML/JS: alerts e o conteúdo de respostaDiv.

PHP: as strings nos throw new Exception() e nos echo json_encode().

================================================================================
12. REFERÊNCIAS E SUPORTE
================================================================================
Caso tenha dúvidas ou precise de mais ajuda, consulte:

Documentação do PHP: https://www.php.net/manual/en/

Documentação do MySQL: https://dev.mysql.com/doc/

Fóruns da comunidade: Stack Overflow (https://stackoverflow.com/)

XAMPP: https://www.apachefriends.org/

Fim do Manual Completo
================================================================================

text
