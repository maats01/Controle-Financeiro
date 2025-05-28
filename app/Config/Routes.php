<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\CategoriesController;
use App\Controllers\TransactionsController;
use App\Controllers\PaymentMethodsController;
use App\Controllers\SituationsController;

/**
 * @var RouteCollection $routes
 */

service('auth')->routes($routes);

// Transactions routes
$routes->get('/', [TransactionsController::class, 'index']);
$routes->get('lancamentos', [TransactionsController::class, 'index']);
$routes->group('lancamentos', static function ($routes)
{
    $routes->post('criar', [TransactionsController::class, 'create']);
    $routes->put('editar/(:num)', [TransactionsController::class, 'edit']);
    $routes->delete('deletar/(:num)', [TransactionsController::class, 'delete']);
});

// Categories routes
$routes->get('/categorias', [CategoriesController::class, 'index']);

// Situations routes
$routes->get('/situacoes', [SituationsController::class, 'index']);

// PaymentMethods routes
$routes->get('/formas-de-pagamento', [PaymentMethodsController::class, 'index']);