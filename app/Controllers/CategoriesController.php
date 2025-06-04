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
        $data = [
            'title' => 'Criar categoria',
        ];
        return view('categories/create', $data);
    }

    public function createPost()
    {
        $model = model(categoryModel::class);

        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'type' => 'required|in_list[0,1]',
        ];

        if(! $this -> validate($rules)){
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $category = new Category();
        $category->fill($this->request->getPost());
        $category->type = (int) $this->request->getPost('type');

        if($model->save($category)){
          session()->setFlashdata('success', 'Categoria adicionada com sucesso!');
          return redirect()->to('/admin/categorias');  
        }else{
            session()->setFlashdata('error', 'Erro ao adicionar categoria. Tente novamente.');
            return redirect()->back()->withInput();
        }
    }

    public function edit(int $id)
    {
        // TODO
    }

    public function delete(int $id)
    {
        $model = model(CategoryModel::class);
        
        if(empty($id)){
            session()->setFlashdata('error','Categoria não encontrada para exclusão.');
            return redirect()->to('admin/categorias');
        }
        
        if($model->delete($id)){
            session()->setFlashdata('success', 'Categoria excluída com sucesso!');
        }else{
            session()->setFlashdata('error', 'Erro ao excluir categoria. Tente novamente mais tarde!');
        }

        return redirect()->to('/admin/categorias');

    }
}
