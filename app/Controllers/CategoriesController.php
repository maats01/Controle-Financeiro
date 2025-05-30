<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoriesController extends BaseController
{
    public function index()
    {
        $model = model(CategoryModel::class);
        $request = $this->request;
        $perPage = (int) ($request->getGet('per_page') ?? 10);
        $searchString = $request->getGet('desc') ?? '';
        $type = is_numeric($request->getGet('type')) ? (int) $request->getGet('type') : null;

        $data = [
            'categories_list' => $model->getFilteredCategories($searchString, $type)->paginate($perPage),
            'per_page' => $perPage,
            'pager' => $model->pager,
            'title' => 'Categorias' 
        ];

        return view('categories/index', $data);
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
