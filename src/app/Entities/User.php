<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\DateTrait;
use App\Traits\ImageTrait;

class User extends Entity
{
    use DateTrait;
    use ImageTrait;

    protected $dates = [
        'created_at',
        'deleted_at'
    ];


    public function setCreatedAt(string $date)
    {
        $this->attributes['created_at'] = ($date === '-' || empty($date)) ? null : $date;

        return $this;
    }
    //This function encrypts password
    public function setPassword(string $password)
    {
        //Encrypt password before saving to database
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    public function getIdentity(): string
    {
        return ucfirst($this->attributes['firstname']) . ' ' . strtoupper($this->attributes['lastname']);
    }

    public function isAdmin(): bool
    {
        return isset($this->attributes['role']) && $this->attributes['role'] === 'ADMIN';
    }

    public function isSeller(): bool
    {
        return isset($this->attributes['role']) && $this->attributes['role'] === 'SELLER';
    }

    public function getFormattedRegistrationDate()
    {
        return $this->formatDate($this->attributes['created_at'] ?? null, false);
    }

}
