<?php

namespace App\Models;

class Account
{
    public function __construct(
        private int $id,
        private string $name,
        private string $email,
        private string $username,
        private string $password
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
