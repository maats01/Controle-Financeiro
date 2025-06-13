<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class UsersController extends BaseController
{
    private array $createUserRules = [
        'username' => [
            'label' => 'Auth.username',
            'rules' => [
                'required',
                'max_length[30]',
                'min_length[3]',
                'regex_match[/\A[a-zA-Z0-9\.]+\z/]',
                'is_unique[users.username]',
            ],
        ],
        'email' => [
            'label' => 'Auth.email',
            'rules' => [
                'required',
                'max_length[254]',
                'valid_email',
                'is_unique[auth_identities.secret]',
            ],
        ],
        'password' => [
            'label' => 'Auth.password',
            'rules' => 'required|max_byte[72]|strong_password[]',
            'errors' => [
                'max_byte' => 'Auth.errorPasswordTooLongBytes'
            ]
        ],
        'password_confirm' => [
            'label' => 'Auth.passwordConfirm',
            'rules' => 'required|matches[password]',
        ],
    ];

    private array $errorMessages = [
        'username' => [
            'required' => 'O nome de usuário é obrigatório.',
            'max_length' => 'O nome de usuário deve ter no máximo 30 caracteres',
            'min_length' => 'O nome de usuário deve ter no mínimo 3 caracteres',
            'is_unique' => 'O nome de usuário fornecido está indisponível'
        ],
        'email' => [
            'required' => 'O email é obrigatório',
            'max_length' => 'O email deve ter no máximo 254 caracteres',
            'valid_email' => 'O email deve ser válido',
            'is_unique' => 'O email fornecido está indisponível'
        ],
        'password' => [
            'required' => 'A senha é obrigatória',
            'strong_password' => 'A senha deve ser uma senha forte'
        ],
        'password_confirm' => [
            'required' => 'É necessário confirmar a senha',
            'matches' => 'As senhas devem ser iguais'
        ],
    ];

    public function index()
    {
        $model = model(\App\Models\UserModel::class);
        $request = $this->request;

        $searchForUsername = $request->getGet('username') ?? '';
        $searchForEmail = $request->getGet('email') ?? '';
        $isActive = is_numeric($request->getGet('active')) ? (int) $request->getGet('active') : null;
        $sortBy = $request->getGet('sort') ?? 'id';
        $sortOrder = $request->getGet('order') ?? 'DESC';
        $perPage = $request->getGet('per_page') ?? 10;

        $data = [
            'title' => 'Usuários',
            'per_page' => $perPage,
            'users_list' => $model->getFilteredUsers($searchForUsername, $searchForEmail, $isActive, $sortBy, $sortOrder)->paginate($perPage),
            'pager' => $model->pager,
        ];

        return view('users/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Criar usuário',
        ];

        return view('users/create', $data);
    }

    public function createPost()
    {
        $model = auth()->getProvider();
        $request = $this->request;

        if (! $this->validate($this->createUserRules, $this->errorMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $user = new User($request->getPost());

        if ($model->save($user)) {
            $user = $model->findByID($model->getInsertID());
            $model->addToDefaultGroup($user);
            session()->setFlashdata('success', 'Usuário criado com sucesso!');

            return redirect()->to('/admin/usuarios');
        } else {
            session()->setFlashdata('error', 'Erro ao adicionar o novo usuário. Verifique os dados e tente novamente.');
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
    }
}
