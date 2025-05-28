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
        $data = [
            'title' => 'Situações',
            'situations_list' => $model->findAll(),
        ]; 

        return view('templates/header', $data)
            . view('situations/index');
    }
}
