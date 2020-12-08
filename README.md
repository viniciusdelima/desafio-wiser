# Desafio Wiser

## Desenvolvimento realizado com: 
### TDD
### PHPUnit para testes unitários
### Factory Design Pattern
### PSR's 1, 4, e 12
### PHP 7.2
### JavaScript Puro


## Instalação

Windows + R > Powershell > Navegue até a pasta da aplicação

digite no terminal "composer install" para instalar as dependências

Renomear o arquivo .env.example e inserir as credenciais para uso com o Box.com 
e caminho para o arquivo de teste a ser upado nos testes unitários.

Instânciar o servidor interno do php com "php -S localhost:8000"

Executar o migration acessando no navegador "http://localhost:8000/database/migrations/create_users_table.php"

## Testes Unitários

Para os testes unitários, digite no terminal ".\vendor\bin\phpunit --colors=always" para suite de testes

Para um relatório resumido ".\vendor\bin\phpunit --colors=always --testdox"

### Rotas disponíveis

http://{SUA_URL}:{SUA_PORTA}/public/login.php (Login do usuário) (em construção)

http://{SUA_URL}:{SUA_PORTA}/public/register.php (Cadastro do usuário) (em construção)

http://{SUA_URL}:{SUA_PORTA}/public/dashboard.php (Listagem dos arquivos) (Listagem, deleção e upagem de arquivos)

## Design da Aplicação

- api/ Conjuntos de arquivos responsáveis por responder para as requests do front.

- database/migrations/ Conjunto de arquivos responsável pela criação da tabela de usuários do sistema.

- src/ Contendo os fontes das bibliotecas desenvolvidas para login de usuários e comunicação com a Api. Todos passíveis de teste unitário.

- - src/Api Entidades responsáveis pela comunicação com a Api do Box.com.
- - src/Authenticator Entidades responsáveis por realizar a autenticação dos usuários no sistema.
- - src/User Entidades responsáveis por representar o usuário do sistema.

- public/ Conjunto de arquivos de front-end da aplicação.

- tests/ Testes unitários da aplicação.