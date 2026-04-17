<?php

declare(strict_types=1);
final class UserPersistenceDto
{
    private string $id;
    private string $name;
    private string $email;
    private string $password;
    private string $role;
    private string $status;
    public function __construct(
        string $id,
        string $name,
        string $email,
        string $password,
        string $role,
        string $status
    ) {
        $this->id = trim($id);
        $this->name = trim($name);
        $this->email = trim($email);
        $this->password = trim($password);
        $this->role = trim($role);
        $this->status = trim($status);
    }
    public function id(): string
    {
        return $this->id;
    }
    public function name(): string
    {
        return $this->name;
    }
    public function email(): string
    {
        return $this->email;
    }
    public function password(): string
    {
        return $this->password;
    }
    public function role(): string
    {
        return $this->role;
    }
    public function status(): string
    {
        return $this->status;
    }
}
