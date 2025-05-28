<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $users = auth()->getProvider();

        $adminEmail = 'admin2@email.com';
        $adminPassword = '#admin12345#';

        $user = $users->findByCredentials(['email' => $adminEmail]);

        if (!$user)
        {
            $user = new User([
                'username' => 'admin',
                'email' => $adminEmail,
                'password' => $adminPassword
            ]);

            $users->save($user);

            $user = $users->findById($users->getInsertID());

            $user->addGroup('admin');
            $user->activate();
        }
    }
}
