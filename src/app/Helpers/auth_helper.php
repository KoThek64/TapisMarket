<?php

use App\Models\AdministrateurModel;
use App\Models\VendeurModel;
use App\Models\ClientModel;
use App\Enums\UserRole;

function user_role(): ?UserRole
{
    static $currentRole = null;
    if ($currentRole !== null) return $currentRole;

    $session = session();
    if (!$session->get('is_logged_in')) return null;

    $currentRole = UserRole::tryFrom($session->get('role') ?? '');
    return $currentRole;
}

function user_data(): ?object
{
    static $data = null;
    if ($data !== null) return $data;

    $role = user_role();
    $id = session()->get('user_id');

    if (!$role || !$id) return null;

    $model = match($role) {
        UserRole::ADMIN   => new AdministrateurModel(),
        UserRole::VENDEUR => new VendeurModel(),
        UserRole::CLIENT  => new ClientModel(),
    };

    $data = $model->find($id);
    return $data;
}

function login_user(int $id, UserRole $role)
{
    session()->set([
        'user_id'      => $id,
        'role'         => $role->value, // IMPORTANT: on stocke la string 'admin'
        'is_logged_in' => true
    ]);
}