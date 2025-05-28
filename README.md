# Controle-Financeiro

## Requisitos
- Composer

## Instalação

### 1. Clonar o repositório
```bash
git clone https://github.com/maats01/Controle-Financeiro.git
```

### 2. Instalar dependências
```bash
composer update
```

### 3. Executar migrations
```bash
php spark migrate -n 'CodeIgniter\Shield'
php spark migrate -n 'CodeIgniter\Settings'
php spark migrate
```
Obs.: para as migrations funcionarem, é necessário ter um arquivo *.env* com os dados básicos para conexão com o banco de dados.  

### 4. Executar os seeders
```bash
php spark db:seed DatabaseSeeder
```

### 5. Testar a aplicação
```bash
php spark serve
```
