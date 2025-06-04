<?php

use App\Controllers\ApiController;
use CodeIgniter\Router\RouteCollection;
use App\Controllers\CategoriesController;
use App\Controllers\TransactionsController;
use App\Controllers\PaymentMethodsController;
use App\Controllers\SituationsController;
use App\Controllers\UsersController;

/**
 * @var RouteCollection $routes
 */

service('auth')->routes($routes);

// Api routes
$routes->group('api', static function ($routes)
{
    $routes->get('categorias', [ApiController::class, 'getCategories']);
    $routes->get('situacoes', [ApiController::class, 'getSituations']);
});

// Transactions routes
$routes->get('/', [TransactionsController::class, 'dashboard']);
$routes->get('dashboard', [TransactionsController::class, 'dashboard']);
$routes->get('lancamentos', [TransactionsController::class, 'index']);
$routes->group('lancamentos', static function ($routes)
{
    $routes->get('criar', [TransactionsController::class, 'create']);
    $routes->post('criar', [TransactionsController::class, 'createPost']);
    $routes->post('editar/(:num)', [TransactionsController::class, 'edit/$1']);
    $routes->post('deletar/(:num)', [TransactionsController::class, 'delete/$1']);
});

$routes->group('admin', ['filters' => 'group:admin'], static function ($routes)
{
    // Categories routes
    $routes->get('categorias', [CategoriesController::class, 'index']);
    $routes->group('categorias', static function ($routes)
    {
        $routes->get('criar', [categoriesController::class, 'create']);
        $routes->post('salvar', [CategoriesController::class, 'createPost']);
        $routes->post('editar/(:num)', [CategoriesController::class, 'edit/$1']);
        $routes->post('deletar/(:num)', [CategoriesController::class, 'delete/$1']);
    });

    // Situations routes
    $routes->get('situacoes', [SituationsController::class, 'index']);
    $routes->group('situacoes', static function ($routes)
    {   
        $routes->get('criar', [SituationsController::class, 'create']);
        $routes->post('salvar', [SituationsController::class, 'createPost']);
        $routes->post('editar/(:num)', [SituationsController::class, 'edit/$1']);
        $routes->post('deletar/(:num)', [SituationsController::class, 'delete/$1']);
    });

    // PaymentMethods routes
    $routes->get('formas-de-pagamento', [PaymentMethodsController::class, 'index']);
    $routes->group('formas-de-pagamento', static function ($routes)
    {
        $routes->get('criar', [PaymentMethodsController::class, 'create']);
        $routes->post('salvar', [PaymentMethodsController::class, 'createPost']);
        $routes->post('editar/(:num)', [PaymentMethodsController::class, 'edit/$1']);
        $routes->post('deletar/(:num)', [PaymentMethodsController::class, 'delete/$1']);
    });

    // Users management routes for admins
    $routes->get('usuarios', [UsersController::class, 'index']);
    $routes->group('usuarios', static function ($routes)
    {
        $routes->get('criar', [UsersController::class, 'create']);
        $routes->post('salvar', [UsersController::class, 'createPost']);
        $routes->post('editar/(:num)', [UsersController::class, 'edit/$1']);
        $routes->post('deletar/(:num)', [UsersController::class, 'delete/$1']);
    });
});
