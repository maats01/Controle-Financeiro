<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SituationModel;
use CodeIgniter\HTTP\ResponseInterface;

class SituationsController extends BaseController
{
    public function index()
    {
        $model = model(SituationModel::class);
        $request = $this->request;
        $perPage = (int) ($request->getGet('per_page') ?? 10);

        $data = [
            'title' => 'Situações',
            'situations_list' => $model->paginate($perPage),
            'per_page' => $perPage,
            'pager' => $model->pager,
        ]; 

        return view('templates/header', $data)
            . view('situations/index')
            . view('templates/footer');
    }
}
