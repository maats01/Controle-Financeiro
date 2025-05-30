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
}
