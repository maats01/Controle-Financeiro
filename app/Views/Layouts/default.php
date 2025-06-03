<?php
$current_path = current_url(true)->getPath();

if (!function_exists('isActive')) {
    function isActive($page_uri_segment, $current_path_full)
    {
        if ($page_uri_segment !== '/' && strpos($page_uri_segment, '/') !== 0) {
            $page_uri_segment = '/' . $page_uri_segment;
        }

        if ($page_uri_segment === '/') {
            return ($current_path_full === '/' || $current_path_full === '/dashboard');
        }

        if (strpos($current_path_full, $page_uri_segment) === 0) {
            $segmentLength = strlen($page_uri_segment);
            if (strlen($current_path_full) === $segmentLength || substr($current_path_full, $segmentLength, 1) === '/') {
                return true;
            }
        }
        return false;
    }
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

    <title><?= $this->renderSection('title') ?: (isset($title) ? esc($title) . ' | ' : '') ?>Controle Financeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/custom_style.css') ?>">

    <?= $this->renderSection('styles') ?>
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="sidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
                <div class="sidebar-brand-icon rotate-n-15"> <i class="fas fa-wallet"></i> </div>
                <div class="sidebar-brand-text mx-3">Controle Financeiro</div>
            </a>
            <hr class="sidebar-divider my-0">

            <li class="nav-item <?= isActive('/', $current_path) || isActive('dashboard', $current_path) ? 'active' : '' ?>">
                <a class="nav-link" href="/dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item <?= isActive('lancamentos', $current_path) ? 'active' : '' ?>">
                <a class="nav-link" href="/lancamentos">
                    <i class="fas fa-fw fa-exchange-alt"></i> <span>Lançamentos</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <?php if (auth()->loggedIn() && auth()->user()->inGroup('admin')): ?>
                <div class="sidebar-heading">Admin</div>
                <li class="nav-item <?= isActive('admin/categorias', $current_path) ? 'active' : '' ?>">
                    <a class="nav-link" href="/admin/categorias"> <i class="fas fa-fw fa-tags"></i> <span>Categorias</span></a>
                </li>
                <li class="nav-item <?= isActive('admin/situacoes', $current_path) ? 'active' : '' ?>">
                    <a class="nav-link" href="/admin/situacoes"> <i class="fas fa-fw fa-check-circle"></i> <span>Situações</span></a>
                </li>
                <li class="nav-item <?= isActive('admin/formas-de-pagamento', $current_path) ? 'active' : '' ?>">
                    <a class="nav-link" href="/admin/formas-de-pagamento"> <i class="fas fa-fw fa-credit-card"></i> <span>Formas Pag.</span></a>
                </li>
                <li class="nav-item <?= isActive('admin/usuarios', $current_path) ? 'active' : '' ?>">
                    <a class="nav-link" href="/admin/usuarios"> <i class="fas fa-fw fa-users"></i> <span>Usuários</span></a>
                </li>
                <hr class="sidebar-divider d-none d-md-block">
            <?php endif; ?>
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
                                    <?php if (auth()->loggedIn()): ?>
                                        <?= esc(auth()->user()->username) ?>
                                    <?php else: ?>
                                        Visitante
                                    <?php endif; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="https://via.placeholder.com/60/<?= auth()->loggedIn() ? '4e73df' : '777' ?>/fff?text=<?= auth()->loggedIn() ? strtoupper(substr(auth()->user()->username, 0, 1)) : 'V' ?>">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <?php if (auth()->loggedIn()): ?>
                                    <a class="dropdown-item" href="/logout" data-toggle="modal" data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Sair
                                    </a>
                                <?php else: ?>
                                    <a class="dropdown-item" href="/login">
                                        <i class="fas fa-sign-in-alt fa-sm fa-fw mr-2 text-gray-400"></i> Login
                                    </a>
                                <?php endif; ?>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    <?= $this->renderSection('content') ?> </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Seu App Financeiro <?= date('Y') ?></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top" style="display: none;"> <i class="fas fa-angle-up"></i></a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Pronto para Sair?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecione "Sair" abaixo se você está pronto para encerrar sua sessão atual.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="<?= site_url('logout') ?>">Sair</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Scroll to top button appear
        $(document).scroll(function() {
            var scrollDistance = $(this).scrollTop();
            if (scrollDistance > 100) {
                $('.scroll-to-top').fadeIn();
            } else {
                $('.scroll-to-top').fadeOut();
            }
        });

        $(document).on('click', 'a.scroll-to-top', function(event) {
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: ($($anchor.attr('href')).offset().top)
            }, 700);
            event.preventDefault();
        });

        $("#sidebarToggleTop").on('click', function(e) {
            $("body").toggleClass("sidebar-toggled");
            $("#sidebar").toggleClass("toggled");
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>