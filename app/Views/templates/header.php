<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle Financeiro</title>
    <style>
        /* Estilos básicos para a navbar (opcional, pode ser personalizado) */
        .navbar {
            background-color: #f8f9fa; /* Cor de fundo */
            padding: 10px 20px;
            border-bottom: 1px solid #e7e7e7;
        }
        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .navbar li {
            float: left;
        }
        .navbar li a {
            display: block;
            color: #333;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar li a:hover {
            background-color: #ddd;
        }
        .navbar-brand {
            font-size: 1.5em;
            font-weight: bold;
            float: left;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand">Controle Financeiro</a>
        <ul>
            <li><a href="/lancamentos">Lançamentos</a></li>
            <li><a href="/categorias">Categorias</a></li>
            <li><a href="/situacoes">Situações</a></li>
            <li><a href="/formas-de-pagamento">Formas de Pagamento</a></li>
        </ul>
    </nav>

    <h1><?= esc($title) ?></h1>
</body>
</html>