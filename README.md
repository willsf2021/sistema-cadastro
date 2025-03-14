# 🚀 Sistema de Cadastro - Laravel + Vue.js

Sistema de Cadastro é uma aplicação full-stack construída com **Laravel** no backend e **Vue.js** no frontend, utilizando **Vite** para o processo de build e hot-reloading. O projeto foi desenvolvido como um teste para a vaga de desenvolvimento da **ABE3 Software Group** e permite o cadastro, gerenciamento e visualização de pessoas, cargos e relações entre cargos e pessoas, com uma interface moderna e responsiva.

---

## 🛠 Tecnologias Utilizadas

- **Backend**: ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) (PHP)  
- **Frontend**: ![Vue.js](https://img.shields.io/badge/Vue.js-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white) (JavaScript) com ![Vite](https://img.shields.io/badge/Vite-B73BFE?style=for-the-badge&logo=vite&logoColor=white)  
- **Estilização**: ![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)  
- **Banco de Dados**: ![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)  
- **Gerenciador de Pacotes**: ![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white) (para o backend) e ![npm](https://img.shields.io/badge/npm-CB3837?style=for-the-badge&logo=npm&logoColor=white)/![Yarn](https://img.shields.io/badge/Yarn-2C8EBB?style=for-the-badge&logo=yarn&logoColor=white) (para o frontend)

---

## [ 📷 Screenshots do Projeto](/screenshots.md).
## [ Jornada do Desenvolvimento | YouTube](https://youtu.be/X9byWdpYbPE).



## 🚀 Como Rodar o Projeto Localmente

### 1. Clone o Repositório

Clone o repositório para sua máquina local:

```bash
git clone https://github.com/willsf2021/sistema-cadastro.git
```

### 2. Instalar Dependências do Backend (Laravel)

Antes de prosseguir, você precisa instalar o PHP e garantir que ele tenha suporte ao PostgreSQL.

#### 2.1. Instalar o PHP

Caso ainda não tenha o PHP instalado, siga os passos abaixo:

- **Windows**: Baixe e instale o [PHP](https://windows.php.net/download/). Alternativamente, use um pacote como [XAMPP](https://www.apachefriends.org/pt_br/index.html) ou [Laragon](https://laragon.org/).
- **Linux** (Ubuntu/Debian):
  ```bash
  sudo apt update && sudo apt install php php-cli php-pgsql
  ```
- **MacOS** (via Homebrew):
  ```bash
  brew install php
  ```

Verifique se o PHP está instalado corretamente com:

```bash
php -v
```

#### 2.2. Ativar o Driver do PostgreSQL no `php.ini`

Após instalar o PHP, você precisa habilitar o driver do PostgreSQL no arquivo `php.ini`:

1. Encontre o arquivo `php.ini`:
   - No Windows, ele geralmente está em `C:\xampp\php\php.ini` ou `C:\laragon\bin\php\php.ini`.
   - No Linux/Mac, pode estar em `/etc/php/8.x/cli/php.ini`.
2. Abra o arquivo e descomente (remova o `;`) da linha:
   ```ini
   extension=pdo_pgsql
   extension=pgsql
   ```
3. Salve o arquivo e reinicie seu servidor Apache ou PHP.
4. Teste se a extensão está ativa:
   ```bash
   php -m | grep pgsql
   ```
   Se `pgsql` e `pdo_pgsql` aparecerem na lista, o driver está ativado.

### 3. Configuração do Banco de Dados

**Banco de Dados**: PostgreSQL  
**Nome do Banco de Dados**: `sistema_cadastro`  
**Usuário do Banco de Dados**: `postgres`  
**Senha do Banco de Dados**: `teste1`

Se você ainda não tem o PostgreSQL instalado, [aqui estão as instruções de instalação](https://www.postgresql.org/download/).

#### 3.1. Criação do Banco de Dados

```bash
psql -U postgres
CREATE DATABASE sistema_cadastro;
```

### 4. Configuração do Backend (Laravel)

### 4.1 Instalando o Composer

Antes de continuar, certifique-se de ter o **Composer** instalado no seu sistema. Caso ainda não tenha, siga as instruções no site oficial:

🔗 [Instalar Composer](https://getcomposer.org/download/)

#### 4.2. Instalar Dependências do Backend

```bash
cd backend
composer install
```

#### 4.3. Configurar o `.env`

```bash
cp .env.example .env
```

#### 4.4. Migrar as Migrations para o Banco de Dados

```bash
php artisan migrate
```

#### 4.5. Rodar o Servidor Backend

```bash
php artisan serve
```

O servidor estará disponível em `http://127.0.0.1:8000`.

### 5. Configuração do Frontend (Vue.js com Vite)

#### 5.1. Instalar Dependências do Frontend

```bash
cd frontend
npm install
# ou
yarn install
```

#### 5.2. Rodar o Servidor de Desenvolvimento com Vite

```bash
npm run dev
# ou
yarn dev
```

Por padrão, o frontend estará disponível em `http://localhost:5173`.

---

## 🔧 Funcionalidades

- **Cadastro de Pessoas**: Permite registrar novas pessoas com nome, e-mail e outros dados.
- **Cadastro de Cargos**: Permite adicionar diferentes cargos que as pessoas podem ocupar.
- **Vinculação e Histórico de Cargos**: Permite cadastrar cargos com data de início, armazenar e editar histórico de cargos, consultar pessoas com o último cargo e visualizar o histórico de cargos de uma pessoa específica.

---

## 🐛 Bugs Conhecidos

Não há bugs conhecidos no momento.

---

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - consulte o arquivo LICENSE para mais detalhes.

---

## 📞 Contato

**Email**: [willsf2015@hotmail.com](mailto:willsf2015@hotmail.com)

---

## 🚀 Boas práticas de desenvolvimento

- **Backend**: Laravel, migrations, validações e autenticação simples.
- **Frontend**: Vue.js com gerenciamento de estado via Vuex (caso esteja implementado).
