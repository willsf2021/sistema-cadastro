# 🚀 Sistema de Cadastro - Laravel + Vue.js

Sistema de Cadastro é uma aplicação full-stack construída com **Laravel** no backend e **Vue.js** no frontend, utilizando **Vite** para o processo de build e hot-reloading. O projeto foi desenvolvido como um teste para a vaga de desenvolvimento da **ABE3 Software** e permite o cadastro, gerenciamento e visualização de pessoas, cargos e relações entre cargos e pessoas, com uma interface moderna e responsiva.

---

## 🛠 Tecnologias Utilizadas

- **Backend**: ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) (PHP)  
- **Frontend**: ![Vue.js](https://img.shields.io/badge/Vue.js-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white) (JavaScript) com ![Vite](https://img.shields.io/badge/Vite-B73BFE?style=for-the-badge&logo=vite&logoColor=white)  
- **Estilização**: ![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)  
- **Banco de Dados**: ![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)  
- **Gerenciador de Pacotes**: ![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white) (para o backend) e ![npm](https://img.shields.io/badge/npm-CB3837?style=for-the-badge&logo=npm&logoColor=white)/![Yarn](https://img.shields.io/badge/Yarn-2C8EBB?style=for-the-badge&logo=yarn&logoColor=white) (para o frontend)

---

## 🚀 Como Rodar o Projeto Localmente

### 1. Clone o Repositório

Clone o repositório para sua máquina local:

```bash
git clone https://github.com/willsf2021/sistema-cadastro.git
```
### 2. Instalando o Composer

Antes de continuar, certifique-se de ter o **Composer** instalado no seu sistema. Caso ainda não tenha, siga as instruções no site oficial:

🔗 [Instalar Composer](https://getcomposer.org/download/)

### 3. Configuração do Banco de Dados

**Banco de Dados**: PostgreSQL  
**Nome do Banco de Dados**: sistema_cadastro  
**Usuário do Banco de Dados**: postgres  
**Senha do Banco de Dados**: teste1  

Se você ainda não tem o PostgreSQL instalado, [aqui estão as instruções de instalação](https://www.postgresql.org/download/).

#### 3.1. Criação do Banco de Dados

No PostgreSQL, crie o banco de dados com o seguinte comando:

```bash
psql -U postgres
CREATE DATABASE sistema_cadastro;
```


### 4. Configuração do Backend (Laravel)

#### 4.1. Instalar Dependências do Backend

Navegue até a pasta do backend e instale as dependências do Laravel com Composer:

```bash
cd backend
composer install
```

#### 4.2. Configuração do `.env`

O arquivo `.env.example` já contém as informações necessárias para conectar ao banco de dados. Basta copiar o arquivo para o nome `.env`:

```bash
cp .env.example .env
```

#### 4.3. Migrar as migrations para o Banco de Dados

Para rodar o as Migrations no Banco de Dados, execute o seguinte comando:

```bash
php artisan migrate
```

#### 4.4. Rodar o Servidor Backend

Para rodar o servidor Laravel localmente, execute o seguinte comando:

```bash
php artisan serve
```

O servidor estará disponível em `http://127.0.0.1:8000`.

### 5. Configuração do Frontend (Vue.js com Vite)

#### 5.1. Instalar Dependências do Frontend

Navegue até a pasta do frontend e instale as dependências com npm ou yarn:

```bash
cd frontend
npm install
# ou
yarn install
```


#### 5.2. Rodar o Servidor de Desenvolvimento com Vite

Para rodar o servidor de desenvolvimento do Vue.js com Vite, execute:

```bash
npm run dev
# ou
yarn dev
```

Por padrão, o frontend estará disponível em `http://localhost:5173`.

## 🔧 Funcionalidades
- **Cadastro de Pessoas**: Permite registrar novas pessoas com nome, e-mail e outros dados.
- **Cadastro de Cargos**: Permite adicionar diferentes cargos que as pessoas podem ocupar.
- **Vinculação e Histórico de Cargos**: Permite cadastrar de Cargo com Data de Início, Armazenamento e Edição de Histórico de Cargo, Tela de Consulta de Pessoas com Último Cargo e Consulta de Histórico de Cargo de Pessoa Específica.

## 🐛 Bugs Conhecidos

Não há bugs conhecidos no momento.

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - consulte o arquivo LICENSE para mais detalhes.

## 📞 Contato


**Email**: willsf2015@hotmail.com

## 🚀 Boas práticas de desenvolvimento
- **Backend**: Laravel, migrando dados, validações, e autenticação simples.
- **Frontend**: Vue.js com gerenciamento de estado via Vuex (caso esteja implementado).

Este README reflete as configurações e orientações para rodar o projeto localmente, com o backend configurado para conectar ao banco de dados PostgreSQL e o frontend sendo executado com Vite na porta 5173.
