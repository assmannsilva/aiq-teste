# AIQ-TESTE API

Back-end do sistema **AIQ-Teste**, desenvolvido com [Laravel](https://laravel.com/) e estruturado como uma **API RESTful**.

## 🧾 Sobre o Projeto

- Esse projeto foi desenvolvido buscando maior disponibilidade e performance para o desafio. 
- Para isso implementei um banco de dados auxiliar de produtos para ter a disponibilidade desses dados caso a API apresente instabilidades
- Também foi implementado um cache para os Produtos para evitar chamadas a FakeStore

- Foram adicionados também os Repositories e Services Patterns para maior escalabilidade e legilibilidade
- Foi implementado injeção de dependência pra facilitar os testes automatizados, ao invés dos facades

- Também foi utilizado o MakeFile, normalmente vem instalado no Linux, mas caso não esteja tem nesse [link](https://www.geeksforgeeks.org/installation-guide/how-to-install-make-on-ubuntu/) um tutorial


Segue também link do Swagger da API depois de estar com o programa rodando http://localhost:8000/api/documentation

## 🚀 Tecnologias Utilizadas

- **PHP 8.4**
- **Laravel 12**
- **Laravel Sanctum** – autenticação Bearer Token
- **PostgreSQL** – banco de dados relacional
- **Redis** – cache
- **Docker + Docker Compose**
- **Makefile** – automação de comandos
- **Pest** – testes

## 📂 Estrutura de Diretórios

- app/
- Http/
- Controllers/ # Controladores da API
- Models/ # Modelos Eloquent
- Services/ # Lógica de negócio
- Exceptions/
- Repositories/
- External/ # Chamada na Fakestore

- routes/
- api.php # Rotas da API

- database/
- migrations/ # Estrutura do banco

## ▶️ Executando com Docker

Antes de mais nada, cole o conteúdo de .env.example para dentro do .env

> **Pré-requisitos:** Docker e Docker Compose instalados.

```bash
# Subir os containers
make up

# Instalar dependências
make install

# Rodar migrations
make migrate

# Acessar o container PHP
make bash

# Gerar Key (dentro do container)
php artisan key:generate
---
```
### 6. **🧪 Testes**

```bash
make test

---
```
## 📥 Endpoints Principais

### 🧑 Clientes

| Método | Rota                  | Descrição                      |
|--------|-----------------------|--------------------------------|
| POST   | /clients  | Criação do cliente, retorna um Bearer Token     |

### 🔐 Endpoints Protegidos (`auth:sanctum`)

#### 🧑 Clientes

| Método | Rota                         | Descrição                        |
|--------|------------------------------|----------------------------------|
| GET   | /clients/me             | Retorna o cliente autenticado     |
| PATCH   | /clients/me       | Atualiza nome e/ou email do cliente      |
| DELETE    | /clients/me             | Deleta o cliente |


#### 📌 Favoritos

| Método | Rota                         | Descrição                        |
|--------|------------------------------|----------------------------------|
| POST   | /favorites             | Adiciona um novo produto favorito atrelado ao cliente    |
| GET   | /favorites       | Retorna uma lista de favoritos do cliente     |
| DELETE    | /favorites/{id}             | Deleta o produto favorito da lista do cliente do id do Produto na FakeStore |
| GET    | /favorites/{id}             | Retorna o Produto favorito através do id do Produto na FakeStore |



## 👤 Autor

Desenvolvido por **Cauê Assmann Silva**