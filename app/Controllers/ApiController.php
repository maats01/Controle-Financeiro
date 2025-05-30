<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\SituationModel;
use CodeIgniter\API\ResponseTrait;

class ApiController extends BaseController
{
    use ResponseTrait;

    public function getCategories()
    {
        $model = model(CategoryModel::class);
        $searchString = $this->request->getGet('search');

        if ($searchString)
        {
            $data = $model->like('name', $searchString, 'both')->findAll();
        }
        else
        {
            $data = $model->findAll();
        }

        return $this->respond($data);
    }

    public function getSituations()
    {
        $model = model(SituationModel::class);
        $searchString = $this->request->getGet('search');

        if ($searchString)
        {
            $data = $model->like('description', $searchString, 'both')->findAll();
        }
        else
        {
            $data = $model->findAll();
        }

        return $this->respond($data);
    }
}
