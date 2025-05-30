<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;

class UsersController extends BaseController
{
    public function index()
    {
        $model = model(\App\Models\UserModel::class);
        $request = $this->request;
        $searchForUsername = $request->getGet('username') ?? '';
        $searchForEmail = $request->getGet('email') ?? '';
        $isActive = is_numeric($request->getGet('active')) ? (int) $request->getGet('active') : null;

        $perPage = $request->getGet('per_page') ?? 10;

        $data = [
            'title' => 'Usuários',
            'per_page' => $perPage,
            'users_list' => $model->getFilteredUsers($searchForUsername, $searchForEmail, $isActive)->paginate($perPage),
            'pager' => $model->pager,
        ];

        return view('users/index', $data);
    }
}
