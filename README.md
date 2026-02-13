Projeto Laravel + Vue 2 (Vite)

Este projeto é uma aplicação web desenvolvida com Laravel 9 no backend e Vue.js 2 no frontend, utilizando Vite para build dos assets e autenticação via JWT.

📋 Requisitos

Antes de iniciar, certifique-se de ter instalado em sua máquina:

Backend

PHP 8.0.2 ou superior

Composer

MySQL / MariaDB / PostgreSQL

Git

Frontend

Node.js 16+

NPM ou Yarn

📥 Clonando o Repositório
git clone <url-do-repositorio>
cd nome-do-projeto

⚙️ Instalação do Backend (Laravel)
1️⃣ Instalar dependências PHP
composer install

2️⃣ Criar arquivo .env
cp .env.example .env

3️⃣ Gerar chave da aplicação
php artisan key:generate

4️⃣ Configurar banco de dados

Edite o arquivo .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha

5️⃣ Executar migrations
php artisan migrate


Opcional:

php artisan migrate --seed

6️⃣ Gerar chave JWT

Este projeto utiliza autenticação via JWT.

php artisan jwt:secret

7️⃣ Iniciar servidor Laravel
php artisan serve


A aplicação backend ficará disponível em:

http://127.0.0.1:8000

🎨 Instalação do Frontend (Vue 2 + Vite)
1️⃣ Instalar dependências Node
npm install


ou

yarn install

2️⃣ Executar ambiente de desenvolvimento
npm run dev


ou

yarn dev

3️⃣ Build para produção
npm run build

🧰 Tecnologias Utilizadas
Backend

Laravel 9

PHP 8+

Laravel Sanctum

JWT Auth (tymon/jwt-auth)

Laravel UI

Frontend

Vue.js 2.7

Vuex

Vite

Bootstrap 5

Axios

Sass

🧪 Ferramentas de Desenvolvimento

PHPUnit (testes)

Faker (dados fake)

Laravel Pint (padronização de código)

Laravel Sail (Docker – opcional)

🛠️ Comandos Úteis
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

⚠️ Problemas Comuns
Permissão de pastas (Linux / Mac)
chmod -R 775 storage bootstrap/cache

Verificar versões
php -v
node -v
npm -v

📦 Estrutura Básica do Projeto
├── app/
├── database/
├── resources/
│   ├── js/
│   └── sass/
├── routes/
├── public/
├── composer.json
├── package.json
└── vite.config.js