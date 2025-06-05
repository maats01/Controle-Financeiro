<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,

            // 'first_name',
        ];
    }

    public function getFilteredUsers($username = '', $email = '', $isActive = null, $sortBy = 'id', $sortOrder = 'DESC')
    {
        $builder = $this->builder();
        $builder->select('
            users.id,
            users.username,
            ai.secret as email,
            agu.group as group,
            users.active,
            users.last_active,
            users.created_at,
        ');
        $builder->join('auth_identities as ai', 'ai.user_id = users.id AND ai.type = \'email_password\'', 'left');
        $builder->join('auth_groups_users as agu', 'agu.user_id = users.id');

        if ($username != '')
        {
            $builder->like('users.username', $username, 'both');
        }
        
        if ($email != '')
        {
            $builder->like('ai.secret', $email, 'both');
        } 

        if (is_integer($isActive))
        {
            $builder->where('users.active', $isActive);
        }

        $builder->orderBy($sortBy, $sortOrder);

        return $this;
    }
}
