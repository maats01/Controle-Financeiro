<?php
    // --- Lógica para Definir a Página Ativa (Exemplo) ---
    // Você precisará adaptar isto à forma como seu framework/projeto
    // identifica a página atual (ex: pela URL ou por uma variável passada pelo controller)
    
    // Exemplo: Supondo que o controller defina $current_path
    // $current_path = '/lancamentos'; // Isso viria do seu controller

    // Se não tiver uma variável do controller, pode tentar pegar da URL (menos ideal para MVC puro)
    if (!isset($current_path)) {
        $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    function isActive($page_uri_segment, $current_path_full) {
        // Verifica se o segmento da URI está contido no caminho completo
        // Adiciona '/' no início do segmento para evitar correspondências parciais indesejadas (ex: /cat vs /categorias)
        if ($page_uri_segment === '/') { // Caso especial para a página inicial/dashboard
            return ($current_path_full === '/' || $current_path_full === '/index.php' || $current_path_full === '/dashboard');
        }
        return strpos($current_path_full, $page_uri_segment) === 0; // Verifica se começa com o segmento
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Controle Financeiro">
    <meta name="author" content="Seu Nome">

    <title><?= isset($title) ? esc($title) . ' | ' : '' ?>Controle Financeiro</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" type="text/css">
    
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f8f9fc; }
        #wrapper { display: flex; }
        #sidebar { width: 224px; background-color: #4e73df; color: #fff; min-height: 100vh; transition: all 0.3s; }
        #sidebar .sidebar-brand { height: 4.375rem; text-decoration: none; font-size: 1rem; font-weight: 800; padding: 1.5rem 1rem; text-align: center; text-transform: uppercase; letter-spacing: 0.05rem; z-index: 1; color: #fff; }
        #sidebar .nav-item .nav-link { color: rgba(255, 255, 255, 0.8); padding: 0.75rem 1rem; display: block; text-decoration: none; }
        #sidebar .nav-item.active .nav-link { color: #fff; background-color: rgba(0, 0, 0, 0.1); font-weight: 700; }
        #sidebar .nav-item .nav-link:hover { color: #fff; background-color: rgba(0, 0, 0, 0.05); }
        #sidebar .nav-item .nav-link i { margin-right: 0.5rem; }
        #sidebar .sidebar-heading { padding: 0 1rem; margin-top: 1rem; margin-bottom: 0.5rem; font-size: 0.75rem; color: rgba(255, 255, 255, 0.4); text-transform: uppercase; }
        #sidebar hr.sidebar-divider { margin: 1rem 1rem; border-top: 1px solid rgba(255, 255, 255, 0.15); }
        #content-wrapper { display: flex; flex-direction: column; width: 100%; overflow-x: hidden; background-color: #fff; }
        #content { flex: 1 0 auto; }
        #topbar { height: 4.375rem; margin-bottom: 1.5rem; background-color: #fff; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important; }
        .navbar-nav .nav-item .nav-link { color: #858796; }
        .navbar-nav .nav-item .dropdown-menu { right: 0; left: auto; }
        .card { margin-bottom: 1.5rem; border: 1px solid #e3e6f0; border-radius: 0.35rem; }
        .card .card-header { background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; font-weight: 700; }
        .text-gray-800 { color: #5a5c69 !important; }
        .btn-primary { background-color: #4e73df; border-color: #4e73df; }
        .btn-primary:hover { background-color: #2e59d9; border-color: #2653d4; }
        .footer { padding: 2rem 0; text-align: center; color: #858796; background-color: #f8f9fc; }
    </style>

</head>

<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="sidebar">
            <?php $baseUrl = ''; // Ex: '/meuprojeto' ou deixe vazio se estiver na raiz ?>

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= $baseUrl ?>/">
                <div class="sidebar-brand-icon rotate-n-15"> <i class="fas fa-wallet"></i> </div>
                <div class="sidebar-brand-text mx-3">Meu Dinheiro</div>
            </a>
            <hr class="sidebar-divider my-0">
            
            <li class="nav-item <?= isActive($baseUrl.'/', $current_path) || isActive($baseUrl.'/dashboard', $current_path) ? 'active' : '' ?>">
                <a class="nav-link" href="<?= $baseUrl ?>/"> 
                    <i class="fas fa-fw fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item <?= isActive($baseUrl.'/lancamentos', $current_path) ? 'active' : '' ?>">
                <a class="nav-link" href="<?= $baseUrl ?>/lancamentos">
                    <i class="fas fa-fw fa-exchange-alt"></i> <span>Lançamentos</span>
                </a>
            </li>
            
            <!-- 
            <li class="nav-item <?= isActive($baseUrl.'/relatorios', $current_path) ? 'active' : '' ?>">
                <a class="nav-link" href="<?= $baseUrl ?>/relatorios">
                    <i class="fas fa-fw fa-chart-area"></i> <span>Relatórios</span>
                </a>
            </li>
            -->

            <hr class="sidebar-divider">
            
            <?php // if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): // Exemplo de verificação de admin ?>
            <div class="sidebar-heading">Admin</div>
            
            <li class="nav-item <?= isActive($baseUrl.'/categorias', $current_path) ? 'active' : '' ?>"> 
                <a class="nav-link" href="<?= $baseUrl ?>/categorias"> <i class="fas fa-fw fa-tags"></i> <span>Categorias</span></a> 
            </li>
            <li class="nav-item <?= isActive($baseUrl.'/situacoes', $current_path) ? 'active' : '' ?>"> 
                <a class="nav-link" href="<?= $baseUrl ?>/situacoes"> <i class="fas fa-fw fa-check-circle"></i> <span>Situações</span></a> 
            </li>
            <li class="nav-item <?= isActive($baseUrl.'/formas-de-pagamento', $current_path) ? 'active' : '' ?>"> 
                <a class="nav-link" href="<?= $baseUrl ?>/formas-de-pagamento"> <i class="fas fa-fw fa-credit-card"></i> <span>Formas Pag.</span></a> 
            </li>
             <li class="nav-item <?= isActive($baseUrl.'/usuarios', $current_path) ? 'active' : '' ?>"> 
                <a class="nav-link" href="<?= $baseUrl ?>/usuarios"> <i class="fas fa-fw fa-users"></i> <span>Usuários</span></a> 
            </li>
            <?php // endif; ?>
            
            <hr class="sidebar-divider d-none d-md-block">
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php // echo isset($_SESSION['user_name']) ? esc($_SESSION['user_name']) : 'Usuário'; // Exemplo ?>
                                    Nome do Usuário
                                </span>
                                <img class="img-profile rounded-circle" src="https://via.placeholder.com/60/777/fff?text=U" alt="Foto Perfil">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="<?= $baseUrl ?>/perfil"> 
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Perfil 
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal"> 
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Sair 
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">