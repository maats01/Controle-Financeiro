<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Category;
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
        $sortBy = $request->getGet('sort') ?? 'id';
        $sortOrder = $request->getGet('order') ?? 'ASC';

        $data = [
            'categories_list' => $model->getFilteredCategories($searchString, $type, $sortBy, $sortOrder)->paginate($perPage),
            'per_page' => $perPage,
            'pager' => $model->pager,
            'title' => 'Categorias',
        ];

        return view('categories/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Criar categoria',
        ];
        return view('categories/create', $data);
    }

    public function createPost()
    {
        $model = model(categoryModel::class);

        $rules = $model->getValidationRules();
        $messages = $model->getValidationMessages();

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $category = new Category();
        $category->fill($this->request->getPost());
        $category->type = (int) $this->request->getPost('type');

        if ($model->save($category)) {
            session()->setFlashdata('success', 'Categoria adicionada com sucesso!');
            return redirect()->to('/admin/categorias');
        } else {
            session()->setFlashdata('error', 'Erro ao adicionar categoria. Tente novamente.');
            return redirect()->back()->withInput();
        }
    }

    public function edit(int $id)
    {
        $model = model(CategoryModel::class);
        $category = $model->find($id);

        if (empty($category)) {
            session()->setFlashdata('error', 'Categoria não encontrada.');
            return redirect()->to('admin/categorias');
        }

        $data = [
            'title' => 'Editar Categoria',
            'category' => $category
        ];

        return view('categories/edit', $data);
    }

    public function editPost()
    {
        $model = model(CategoryModel::class);

        $postData = $this->request->getPost();

        $rules = $model->getValidationRules();
        $messages = $model->getValidationMessages();

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }
        
        $category = $model->find($postData['id']);

        if (empty($category)) {
            session()->setFlashdata('error', 'Categoria não encontrada para atualização.');
            return redirect()->to('admin/categorias');
        }

        $category->fill($postData);

        if (!$category->haschanged()) {
            session()->setFlashdata('info', 'Nenhuma alteração detectada para a categoria.');
            return redirect()->to('admin/categorias');
        }

        if ($model->save($category)) {
            session()->setFlashdata('success', 'Categoria atualizada com sucesso!');
            return redirect()->to('admin/categorias');
        } else {
            session()->setFlashdata('error', 'Erro ao atualizar a categoria. Verifique os dados e tente novamente.');
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
    }

    public function delete(int $id)
    {
        $model = model(CategoryModel::class);

        if (empty($id)) {
            session()->setFlashdata('error', 'Categoria não encontrada para exclusão.');
            return redirect()->to('admin/categorias');
        }

        if ($model->delete($id)) {
            session()->setFlashdata('success', 'Categoria excluída com sucesso!');
        } else {
            session()->setFlashdata('error', 'Erro ao excluir categoria. Tente novamente mais tarde!');
        }

        return redirect()->to('/admin/categorias');
    }
}
