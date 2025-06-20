<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class UsersController extends BaseController
{
    private array $UserRules = [
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
        'active' => [
            'label' => 'Status',
            'rules' => 'required|in_list[0,1]',
        ],
        'group' => [
            'label' => 'Grupo do Usuário',
            'rules' => 'required|alpha_dash',
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
        'active' => [
            'required' => 'O status é obrigatório.',
            'in_list' => 'O status deve ser 0 (Inativo) ou 1 (Ativo).',
        ],
        'group' => [
            'required' => 'O grupo do usuário é obrigatório.',
            'alpha_dash' => 'O grupo do usuário deve conter apenas caracteres alfanuméricos, sublinhados ou traços.',
            'is_not_unique' => 'O grupo selecionado não é um grupo Shield válido.',
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
        $sortOrder = $request->getGet('order') ?? 'ASC';
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

        if (! $this->validate($this->UserRules, $this->errorMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $user = new User($request->getPost());

        if ($model->save($user)) {
            $user = $model->findByID($model->getInsertID());
            $model->addToDefaultGroup($user);

            $activeStatus = (int) $request->getPost('active');
            $groupName = $request->getPost('group');

            if ($activeStatus === 1) {
                $user->activate(); 
            } else {
                $user->deactivate();
            }
            $model->save($user);

            foreach ($user->getGroups() as $currentGroup) {
                $user->removeGroup($currentGroup);
            }

            $user->addGroup($groupName);
            $model->save($user); 

            session()->setFlashdata('success', 'Usuário criado com sucesso!');

            return redirect()->to('/admin/usuarios');
        } else {
            session()->setFlashdata('error', 'Erro ao adicionar o novo usuário. Verifique os dados e tente novamente.');
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
    }

    public function edit(int $id)
    {
                $model = auth()->getProvider();
        $user = $model->findById($id);

        if (empty($user)) {
            session()->setFlashdata('error', 'Usuário não encontrado.');
            return redirect()->to('/admin/usuarios');
        }

        $data = [
            'title' => 'Editar Usuário',
            'user'  => $user,
        ];

        return view('users/edit', $data);
    }

    public function editPost(int $id)
    {
        $model = auth()->getProvider();
        $request = $this->request;

        $user = $model->findById($id);

        if (empty($user)) {
            session()->setFlashdata('error', 'Usuário não encontrado para edição.');
            return redirect()->to('/admin/usuarios');
        }

        $editRules = $this->UserRules;

        unset($editRules['password']);
        unset($editRules['password_confirm']);        

        $editRules['username']['rules'] = array_map(function($rules) use ($user) {
            if ($rules === 'is_unique[users.username]') {
                return 'is_unique[users.username,id,' . $user->id . ']';
            }
            return $rules;
        }, $editRules['username']['rules']);
        $editRules['email']['rules'] = array_map(function($rules) use ($user) {
            if ($rules === 'is_unique[auth_identities.secret]') {
                return 'is_unique[auth_identities.secret,user_id,' . $user->id . ']';
            }
            return $rules;
        }, $editRules['email']['rules']);

        if (! $model->save($user)) {
            session()->setFlashdata('error', 'Erro ao salvar as alterações do usuário. Verifique os dados e tente novamente.');
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        $user->username = $request->getPost('username');
        $user->active = (int) $request->getPost('active');
        $user->setEmail($request->getPost('email'));

        $newGroupName = $request->getPost('group');
        $currentGroups = $user->getGroups();
        $groupChanged = (! in_array($newGroupName, $currentGroups));

        if (!$user->hasChanged() && !$groupChanged) {
            session()->setFlashdata('info', 'Nenhuma alteração detectada para o usuário.');
            return redirect()->to('/admin/usuarios');
        }

        if (! $model->save($user)) {
            session()->setFlashdata('error', 'Erro ao salvar as alterações do usuário. Verifique os dados e tente novamente.');
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        if ($groupChanged) {
            foreach ($currentGroups as $currentGroup) {
                $user->removeGroup($currentGroup);
            }
            $user->addGroup($newGroupName);
            $model->save($user);
        }
        
        session()->setFlashdata('success', 'Usuário atualizado com sucesso!');
        return redirect()->to('/admin/usuarios');
    }

    public function delete(int $id)
    {
        $model = model(UserModel::class);
        $currentUser = auth()->user();

        $userToDelete = $model->find($id);

        if (empty($userToDelete)) {
            session()->setFlashdata('error', 'Usuário não encontrado.');
            return redirect()->to('/admin/usuarios');
        }

        if ($userToDelete->id === $currentUser->id) {
            session()->setFlashdata('error', 'Você não pode excluir seu próprio usuário.');
            return redirect()->to('/admin/usuarios');
        }

        if (! $currentUser->inGroup('admin')) {
             session()->setFlashdata('error', 'Você não tem permissão para excluir usuários.');
             return redirect()->to('/admin/usuarios');
        }

        if ($model->delete($id)) {
            session()->setFlashdata('success', 'Usuário excluído com sucesso!');
        } else {
            session()->setFlashdata('error', 'Erro ao excluir usuário. Tente novamente mais tarde!');
        }

        return redirect()->to('/admin/usuarios');
    }
}