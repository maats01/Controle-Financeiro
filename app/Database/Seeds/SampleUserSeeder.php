<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class SampleUserSeeder extends Seeder
{
    public function run()
    {
        $users = auth()->getProvider();

        $userEmail = 'usuario.teste@email.com';
        $userPwd = '#teste12345#';

        $user = $users->findByCredentials(['email' => $userEmail]);

        if (!$user)
        {
            $user = new User([
                'username' => 'Usuário Teste',
                'email' => $userEmail,
                'password' => $userPwd
            ]);

            $users->save($user);

            $user = $users->findById($users->getInsertID());

            $user->addGroup('user');
            $user->activate();
        }
    }
}
