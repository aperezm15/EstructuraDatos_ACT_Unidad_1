<?php

declare(strict_types=1);
final class UserEntity
{
    private string $id;
    private string $name;
    private string $email;
    private string $password;
    private string $role;
    private string $status;
    private ?string $createdAt;
    private ?string $updatedAt;
    public function __construct(
        string $id,
        string $name,
        string $email,
        string $password,
        string $role,
        string $status,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = trim($id);
        $this->name = trim($name);
        $this->email = trim($email);
        $this->password = trim($password);
        $this->role = trim($role);
        $this->status = trim($status);
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
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
    public function createdAt(): ?string
    {
        return $this->createdAt;
    }
    public function updatedAt(): ?string
    {
        return $this->updatedAt;
    }
}
