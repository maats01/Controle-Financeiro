<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CategoryModel;

class CategoriesController extends BaseController
{
    public function index()
    {
        $model = model(CategoryModel::class);

        $data = [
            'categories_list' => $model->findAll(),
            'title' => 'Categorias' 
        ];

        return view('templates/header', $data)
            . view('categories/index');
    }

    public function create()
    {
        // TODO
    }

    public function edit(int $id)
    {
        // TODO
    }

    public function delete(int $id)
    {
        // TODO
    }
}
