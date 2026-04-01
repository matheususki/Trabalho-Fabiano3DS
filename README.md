# Trabalho-Fabiano3DS
Trabalho Escolar Feito por Nicolas Anderson e Matheus Suski

================================================================================
                MANUAL COMPLETO DO PROJETO: FORMULÁRIO DE CADASTRO
                          PHP + MySQL + HTML/CSS/JS
================================================================================

Este manual fornece instruções detalhadas para instalar, configurar, utilizar e personalizar o sistema de cadastro de usuários. 
O projeto consiste em um formulário web que valida e armazena dados em um banco de dados MySQL, com foco em segurança e usabilidade.

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
   5.1. Ajustar Credenciais no PHP
   5.2. Colocar os Arquivos no Servidor
6. TESTE DE CONEXÃO
7. UTILIZAÇÃO DO SISTEMA
8. FUNCIONALIDADES DETALHADAS (COM EXEMPLOS DE CÓDIGO)
   8.1. Validação no Front-end (HTML/JS)
   8.2. Validação no Back-end (PHP)
   8.3. Contador de Caracteres para Mensagem
   8.4. Estilização CSS Responsiva
9. SOLUÇÃO DE PROBLEMAS COMUNS (TROUBLESHOOTING)
   9.1. Erro de Acesso Negado ao MySQL (Access Denied)
   9.2. Erro "No such file or directory" ao Conectar
   9.3. Página 404 Not Found
   9.4. PHP não é Processado (Mostra o código fonte)
   9.5. Mensagem de Sucesso mas Dados Não Aparecem no Banco
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
Este sistema foi desenvolvido para ser uma base de cadastro simples, porém completa, com validações tanto no lado do cliente quanto no servidor. 
Ele permite coletar nome, email, senha e uma mensagem (limitada a 250 caracteres), e armazenar essas informações de forma segura em um banco MySQL.
A interface é moderna, responsiva e oferece feedback claro ao usuário.

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
Os arquivos abaixo devem estar dentro de uma mesma pasta no diretório público do servidor web (ex: htdocs/nome_do_projeto/).

| Arquivo              | Descrição                                                                 |
|----------------------|---------------------------------------------------------------------------|
| index.html           | Página principal com o formulário, CSS e JavaScript.                      |
| processar.php        | Script PHP que recebe os dados, valida, aplica hash na senha e insere no MySQL. |
| teste_conexao.php    | (Opcional) Arquivo simples para testar a conexão com o banco.             |
| README.txt           | Este manual.                                                              |

Certifique-se de que todos os arquivos tenham permissões de leitura e execução adequadas no servidor.

================================================================================
4. CONFIGURAÇÃO DO BANCO DE DADOS
================================================================================
Antes de usar o sistema, é necessário criar o banco de dados e a tabela onde os dados serão armazenados.

4.1. Criar o Banco de Dados e a Tabela
----------------------------------------
Acesse o MySQL Workbench, phpMyAdmin ou a linha de comando e execute os seguintes comandos SQL:

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
);

-- Opcional: você pode adicionar índices para melhorar a performance
CREATE INDEX idx_email ON usuarios(email);

Explicação dos campos:
- id: chave primária, auto incrementada.
- nome: armazena o nome do usuário (texto puro).
- email: armazena o e-mail (validado no código).
- senha: armazena o hash da senha gerado pelo PHP.
- mensagem: armazena a mensagem com limite de 250 caracteres.

4.2. Criar um Usuário Específico para o Projeto (Recomendado)
--------------------------------------------------------------
Em vez de usar o usuário root, é mais seguro criar um usuário com permissões apenas para este banco. No MySQL Workbench ou linha de comando:

-- Cria o usuário 'appuser' com senha '123456'
CREATE USER 'appuser'@'localhost' IDENTIFIED BY '123456';

-- Concede todos os privilégios no banco bd_nm para este usuário
GRANT ALL PRIVILEGES ON bd_nm.* TO 'appuser'@'localhost';

-- Atualiza as permissões
FLUSH PRIVILEGES;

Se você estiver usando um servidor remoto, substitua 'localhost' pelo endereço IP de onde as conexões virão.

4.3. Verificar se o MySQL está Rodando
----------------------------------------
- No Windows: abra o "Serviços" (services.msc) e verifique se o serviço "MySQL" ou "MariaDB" está em execução.
- No XAMPP: abra o painel de controle e confira se o MySQL está com status "Running".
- No Linux/Mac: execute `sudo systemctl status mysql` ou `sudo service mysql status`.

Se o MySQL não estiver rodando, inicie-o. No XAMPP, clique em "Start" ao lado de MySQL. Em outros ambientes, use o comando apropriado.

================================================================================
5. CONFIGURAÇÃO DO PROJETO
================================================================================
5.1. Ajustar Credenciais no PHP
--------------------------------
Abra o arquivo `processar.php` e localize as linhas iniciais que definem as variáveis de conexão:

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd_nm";

Substitua pelos valores correspondentes ao seu ambiente:
- $servername: pode ser "localhost", "127.0.0.1" ou o endereço do servidor MySQL.
- $username: "root" ou o nome do usuário que criou (ex: "appuser").
- $password: a senha do usuário. Se não houver senha, mantenha vazio ("").
- $dbname: o nome do banco criado (ex: "bd_nm").

Importante: se você criou um usuário específico, use essas credenciais.

5.2. Colocar os Arquivos no Servidor
--------------------------------------
- Para XAMPP: copie todos os arquivos para `C:\xampp\htdocs\nome_da_pasta\` (ex: `C:\xampp\htdocs\meucadastro\`).
- Para WAMP: copie para `C:\wamp\www\nome_da_pasta\`.
- Para MAMP (Mac): copie para `/Applications/MAMP/htdocs/nome_da_pasta/`.
- Para servidor online: utilize um cliente FTP (FileZilla, etc.) para enviar os arquivos para o diretório público (geralmente `public_html` ou `www`).

Após copiar, o sistema estará acessível via navegador através de um URL como `http://localhost/nome_da_pasta/index.html`.

================================================================================
6. TESTE DE CONEXÃO
================================================================================
Para garantir que o PHP consegue se comunicar com o MySQL, crie um arquivo `teste_conexao.php` com o seguinte conteúdo:

<?php
// Configurações (ajuste conforme seu ambiente)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd_nm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("ERRO: " . $conn->connect_error);
} else {
    echo "SUCESSO: Conectado ao banco de dados!";
}
$conn->close();
?>

Acesse este arquivo pelo navegador, ex: `http://localhost/nome_da_pasta/teste_conexao.php`.
- Se aparecer "SUCESSO: Conectado ao banco de dados!", a configuração está correta.
- Se aparecer um erro, leia a mensagem e verifique as credenciais e se o MySQL está rodando.

================================================================================
7. UTILIZAÇÃO DO SISTEMA
================================================================================
1. Abra o navegador e digite: `http://localhost/nome_da_pasta/index.html`
2. Preencha todos os campos:
   - Nome: apenas letras e espaços. Ex: "João Silva". Números e símbolos serão bloqueados no front-end e também validados no back-end.
   - Email: deve ser um endereço válido, como "usuario@exemplo.com".
   - Senha: qualquer texto. Será armazenada com hash (não é possível visualizar a senha original no banco).
   - Mensagem: até 250 caracteres. Um contador abaixo mostra quantos foram digitados.
3. Clique em "Enviar".
4. Aguarde o feedback:
   - Sucesso: uma mensagem verde "Dados salvos com sucesso!" será exibida e o formulário será resetado.
   - Erro: uma mensagem vermelha indicará o problema (ex: "O nome deve conter apenas letras e espaços.").
5. Para visualizar os dados cadastrados, acesse o banco via phpMyAdmin ou Workbench e consulte a tabela `usuarios`. Você verá o id, nome, email, hash da senha e a mensagem.

================================================================================
8. FUNCIONALIDADES DETALHADAS (COM EXEMPLOS DE CÓDIGO)
================================================================================
8.1. Validação no Front-end (HTML/JS)
--------------------------------------
O campo "nome" utiliza o atributo HTML5 `pattern` e uma validação em JavaScript para impedir números ou caracteres especiais.

<input type="text" id="nome" name="nome" required pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s]+" title="Apenas letras e espaços">

Além disso, no evento `submit` do formulário, há uma verificação adicional:

const nome = document.getElementById('nome').value;
const regexLetras = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;
if (!regexLetras.test(nome)) {
    alert('O nome deve conter apenas letras e espaços.');
    return;
}

8.2. Validação no Back-end (PHP)
---------------------------------
O arquivo `processar.php` valida novamente todos os dados para garantir segurança:

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
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

8.3. Contador de Caracteres para Mensagem
------------------------------------------
No JavaScript, um evento 'input' atualiza um contador e impede que o usuário digite mais de 250 caracteres:

mensagemField.addEventListener('input', function() {
    const caracteres = this.value.length;
    contador.textContent = `${caracteres}/250 caracteres`;
    if (caracteres > 250) {
        this.value = this.value.substring(0, 250);
        contador.textContent = `250/250 caracteres`;
    }
});

8.4. Estilização CSS Responsiva
--------------------------------
O CSS utiliza gradiente, sombras, bordas arredondadas e transições. Exemplo:

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

================================================================================
9. SOLUÇÃO DE PROBLEMAS COMUNS (TROUBLESHOOTING)
================================================================================
9.1. Erro de Acesso Negado ao MySQL (Access Denied)
----------------------------------------------------
Mensagem: "Access denied for user 'root'@'localhost' (using password: YES)" ou "using password: NO".
Causa: credenciais incorretas no arquivo processar.php.
Soluções:
- Verifique se a senha está correta. Se você usa MySQL Workbench sem senha, deixe $password = "";.
- Se mesmo assim não funcionar, crie um novo usuário (conforme item 4.2) e use essas credenciais.
- Tente usar "127.0.0.1" em vez de "localhost" no $servername.

9.2. Erro "No such file or directory" ao Conectar
--------------------------------------------------
Mensagem: "No such file or directory" em ambiente Windows.
Causa: O MySQL pode estar usando um socket diferente ou o host "localhost" não está resolvido corretamente.
Solução:
- Substitua "localhost" por "127.0.0.1" no $servername.
- Verifique se o serviço MySQL está em execução.
- No php.ini, verifique se a extensão mysqli está habilitada (remova o ponto e vírgula antes de extension=mysqli).

9.3. Página 404 Not Found
--------------------------
Causa: o arquivo não está no local correto dentro do servidor web.
Solução:
- Verifique se o arquivo index.html está dentro da pasta do servidor (ex: htdocs/meuprojeto).
- Acesse com o caminho correto: http://localhost/meuprojeto/index.html.
- Verifique se o Apache (ou servidor web) está rodando.

9.4. PHP não é Processado (Mostra o código fonte)
-------------------------------------------------
Causa: O servidor não está configurado para executar PHP, ou o arquivo não tem extensão .php.
Solução:
- Certifique-se de que o arquivo processar.php tem extensão .php.
- Verifique se o Apache tem o módulo PHP carregado.
- No XAMPP, inicie o Apache e o PHP deve estar ativo por padrão.

9.5. Mensagem de Sucesso mas Dados Não Aparecem no Banco
---------------------------------------------------------
Causa: Possível erro na inserção que não foi capturado, ou banco/tabela incorretos.
Solução:
- Verifique os logs do PHP (em C:\xampp\php\logs\php_error_log) ou ative o display_errors.
- Execute manualmente a query de inserção no MySQL para verificar se a tabela existe e tem a estrutura correta.
- Confira se o banco de dados selecionado no PHP é o mesmo onde a tabela foi criada.

================================================================================
10. SEGURANÇA IMPLEMENTADA
================================================================================
O projeto adota várias práticas de segurança:

- Prepared Statements: Usados no mysqli para evitar SQL Injection.
  $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, mensagem) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $nome, $email, $senha_hash, $mensagem);

- Hash de Senha: Utiliza password_hash() com algoritmo BCRYPT, garantindo que a senha não seja armazenada em texto puro.

- Validação de Dados: Tanto no front-end quanto no back-end, garantindo que entradas maliciosas sejam rejeitadas.

- Controle de Erros: As mensagens de erro exibidas ao usuário são genéricas (não revelam detalhes internos do banco). Exceções são capturadas e retornadas como JSON.

- Cabeçalhos: O arquivo PHP define `header('Content-Type: application/json');` para indicar que a resposta é JSON.

================================================================================
11. PERSONALIZAÇÃO AVANÇADA
================================================================================
11.1. Alterar Cores e Estilo
-----------------------------
Edite o CSS dentro da tag <style> em index.html. As cores principais são:
- Gradiente de fundo do body: linear-gradient(135deg, #667eea 0%, #764ba2 100%)
- Cor de fundo do cabeçalho h2: mesmo gradiente.
- Cor de foco nos inputs: #667eea.
Para alterar, substitua os valores hexadecimais.

11.2. Adicionar Novos Campos
-----------------------------
Para adicionar um campo como "telefone":
1. Altere a tabela no MySQL: ALTER TABLE usuarios ADD telefone VARCHAR(20);
2. No index.html, adicione o campo dentro do <form>.
3. No processar.php, capture o valor: $telefone = trim($_POST['telefone'] ?? '');
4. Valide se necessário e adicione no INSERT e no bind_param.

11.3. Alterar Limites de Caracteres
-------------------------------------
Para aumentar o limite da mensagem para 500 caracteres:
- No HTML: maxlength="500"
- No JavaScript: ajuste a condição if (caracteres > 500)
- No PHP: if (strlen($mensagem) > 500) ...

11.4. Traduzir Mensagens
-------------------------
Todas as mensagens de erro e sucesso estão em português. Para alterar, edite os textos em:
- HTML/JS: alerts e o conteúdo de respostaDiv.
- PHP: as strings nos throw new Exception().

================================================================================
12. REFERÊNCIAS E SUPORTE
================================================================================
Caso tenha dúvidas ou precise de mais ajuda, consulte:

- Documentação do PHP: https://www.php.net/manual/en/
- Documentação do MySQL: https://dev.mysql.com/doc/
- Fóruns da comunidade: Stack Overflow (https://stackoverflow.com/)
- XAMPP: https://www.apachefriends.org/

----------------------------------------------------------------------
Fim do Manual Completo
================================================================================
