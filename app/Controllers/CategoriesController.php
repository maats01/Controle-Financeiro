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
        $request = $this->request;
        $perPage = (int) ($request->getGet('per_page') ?? 10);

        $data = [
            'categories_list' => $model->paginate($perPage),
            'per_page' => $perPage,
            'pager' => $model->pager,
            'title' => 'Categorias' 
        ];

        return view('templates/header', $data)
            . view('categories/index')
            . view('templates/footer');
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
