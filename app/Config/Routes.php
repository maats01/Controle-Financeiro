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
    $routes->put('editar/(:num)', [TransactionsController::class, 'edit']);
    $routes->delete('deletar/(:num)', [TransactionsController::class, 'delete']);
});

$routes->group('admin', ['filters' => 'group:admin'], static function ($routes)
{
    // Categories routes
    $routes->get('categorias', [CategoriesController::class, 'index']);
    $routes->group('categorias', static function ($routes)
    {
        $routes->post('criar', [CategoriesController::class, 'create']);
        $routes->put('editar/(:num)', [CategoriesController::class, 'edit']);
        $routes->delete('deletar/(:num)', [CategoriesController::class, 'delete']);
    });

    // Situations routes
    $routes->get('situacoes', [SituationsController::class, 'index']);
    $routes->group('situacoes', static function ($routes)
    {
        $routes->post('criar', [SituationsController::class, 'create']);
        $routes->put('editar/(:num)', [SituationsController::class, 'edit']);
        $routes->delete('deletar/(:num)', [SituationsController::class, 'delete']);
    });

    // PaymentMethods routes
    $routes->get('formas-de-pagamento', [PaymentMethodsController::class, 'index']);
    $routes->group('formas-de-pagamento', static function ($routes)
    {
        $routes->post('criar', [PaymentMethodsController::class, 'create']);
        $routes->put('editar/(:num)', [PaymentMethodsController::class, 'edit']);
        $routes->delete('deletar/(:num)', [PaymentMethodsController::class, 'delete']);
    });

    // Users management routes for admins
    $routes->get('usuarios', [UsersController::class, 'index']);
    $routes->group('usuarios', static function ($routes)
    {
        $routes->post('criar', [UsersController::class, 'create']);
        $routes->put('editar/(:num)', [UsersController::class, 'edit']);
        $routes->delete('deletar/(:num)', [UsersController::class, 'delete']);
    });
});
