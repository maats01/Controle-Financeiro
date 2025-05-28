<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers;

/**
 * @var RouteCollection $routes
 */

service('auth')->routes($routes);
// Transactions routes
$routes->get('/', [TransactionsController::class, 'index']);
$routes->get('transactions', [TransactionsController::class, 'index']);
$routes->group('transactions', static function ($routes)
{
    $routes->post('create', [TransactionsController::class, 'create']);
    $routes->put('edit/(:num)', [TransactionsController::class, 'edit']);
    $routes->delete('delete/(:num)', [TransactionsController::class, 'delete']);
});
