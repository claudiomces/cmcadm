# DJM Distribuidora

Site PHP da DJM com páginas institucionais, catálogo e painel de gerenciamento de peças.

## Requisitos e execução local

- PHP 8.1 ou superior, com a extensão Fileinfo ativada.
- Servidor PHP apontando para este diretório. Para testar localmente, execute `php -S localhost:8000` na pasta do projeto e acesse `http://localhost:8000`.
- O servidor precisa ter permissão de escrita em `storage/products.json` e em `assets/uploads/`.

## Painel de cadastro

A página `cadastrar.php` não aparece na navegação do site. A senha administrativa é armazenada como hash no arquivo `storage/admin.json`, no campo `password_hash`, e validada pelo PHP. Para trocar a senha, gere um novo hash com `php -r "echo password_hash('SUA_SENHA', PASSWORD_DEFAULT), PHP_EOL;"` e atualize esse campo. O diretório `storage` deve permanecer bloqueado para acesso direto pela web.

Em produção, use HTTPS e limite o acesso ao painel conforme as regras do servidor. Os produtos e imagens são persistidos em `storage/products.json` e `assets/uploads/`.
