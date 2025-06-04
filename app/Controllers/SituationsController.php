<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Situation;
use App\Models\SituationModel;
use CodeIgniter\HTTP\ResponseInterface;

class SituationsController extends BaseController
{
    public function index()
    {
        $model = model(SituationModel::class);
        $request = $this->request;
        $perPage = (int) ($request->getGet('per_page') ?? 10);
        $searchString = $request->getGet('desc') ?? '';
        $type = is_numeric($request->getGet('type')) ? (int) $request->getGet('type') : null;

        $data = [
            'title' => 'Situações',
            'situations_list' => $model->getFilteredSituations($searchString, $type)->paginate($perPage),
            'per_page' => $perPage,
            'pager' => $model->pager,
        ]; 

        return view('situations/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Criar situação'
        ];
        return view('situations/create', $data);
    }

    public function createPost()
    {
        $model = model(SituationModel::class);

        $rules = [
            'description' => 'required|min_length[3]|max_length[255]',
            'type' => 'required|in_list[0,1]',
        ];

        if(! $this -> validate($rules)){
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $situation = new Situation();
        $situation->fill($this->request->getPost());
        $situation->type = (int) $this->request->getPost('type');

        if($model->save($situation)){
            session()->setFlashdata('success', 'Situação adicionada com sucesso!');
            return redirect()->to('admin/situacoes');
        }else{
            session()->setFlashdata('error', 'Erro ao adicionar situação. Tente novamente.');
            return redirect()->back()->withInput();
        }
    }

    public function edit(int $id)
    {
        // TODO
    }

    public function delete(int $id)
    {
        $model = model(SituationModel::class);

        if(empty($id)){
            session()->setFlashdata('error', 'Situação não encontrada para exclusão.');
            return redirect()->to('admin/situacoes');
        }

        if($model->delete($id)){
            session()->setFlashdata('success', 'Situação excluída com sucesso!');
        }else{
            session()->setFlashdata('error', 'Erro ao excluir situação. Tente novamente mais tarde!');
        }

        return redirect()->to('admin/situacoes');
    }
}
