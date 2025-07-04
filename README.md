# Controle-Financeiro
Esse sistema foi desenvolvido como projeto final da disciplina de Programação-3 da faculdade.

## Visão geral
A aplicação tem o objetivo de auxiliar o usuário no controle financeiro do mês, permitindo que ele cadastre os ganhos e despesas.

## Requisitos
- PHP 8.1 ou superior
- Composer
- Banco de dados relacional

## Instalação

### 1. Clonar o repositório
```bash
git clone https://github.com/maats01/Controle-Financeiro.git
```

### 2. Instalar dependências
```bash
composer install
```

### 3. Executar migrations
```bash
php spark migrate --all
```
Obs.: para as migrations funcionarem, é necessário ter um arquivo *.env* com as variáveis básicas para o framework se conectar com o banco de dados.  

### 4. Executar os seeders (opcional)
```bash
php spark db:seed DatabaseSeeder
```

### 5. Testar a aplicação
```bash
php spark serve
```
