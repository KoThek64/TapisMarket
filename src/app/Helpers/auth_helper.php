<?php

use App\Models\AdministratorModel;
use App\Models\CustomerModel;
use App\Models\SellerModel;
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

function user_id(): ?int
{
    return session()->get('user_id');
}

function user_data(): ?object
{
    static $data = null;
    if ($data !== null) return $data;

    $role = user_role();
    $id = session()->get('user_id');

    if (!$role || !$id) return null;

    $model = match($role) {
        UserRole::ADMIN   => new AdministratorModel(),
        UserRole::SELLER => new SellerModel(),
        UserRole::CLIENT  => new CustomerModel(),
    };

    $tableName = $model->getTable();
    $data = $model->select("{$tableName}.*, users.email, users.password, users.lastname, users.firstname, users.created_at")
            ->join('users', "users.id = {$tableName}.user_id")
            ->where('users.id', $id)
            ->first();

    return $data;
}

function login_user(int $id, UserRole $role)
{
    session()->regenerate();
    session()->set([
        'user_id'      => $id,
        'role'         => $role->value, // IMPORTANT: on stocke la string 'admin'
        'is_logged_in' => true
    ]);
}

function logout_user()
{
    session()->destroy();
}
