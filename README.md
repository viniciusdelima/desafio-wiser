# Desafio Wiser

## Desenvolvimento realizado com: 
### TDD
### PHPUnit para testes unitários
### Factory Design Pattern
### PSR's 1, 4, e 12
### PHP 7.2

Instalação

Windows + R > Powershell > Navegue até a pasta da aplicação

digite no terminal "composer install" para instalar as dependências

Renomear o arquivo .env.example e inserir as credenciais para uso com o Box.com

Instânciar o servidor interno do php com "php -S localhost:8000"

Executar o migration acessando no navegador "http://localhost:8000/database/migrations/create_users_table.php"

Rotas disponíveis

http://{SUA_URL}:{SUA_PORTA}/public/login.php (Login do usuário)
http://{SUA_URL}:{SUA_PORTA}/public/register.php (Cadastro do usuário)
http://{SUA_URL}:{SUA_PORTA}/public/dashboard.php (Listagem dos arquivos)