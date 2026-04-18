<?php
declare(strict_types=1);
final class CreateUserWebRequest
{
 private string $id;
 private string $name;
 private string $email;
 private string $password;
 private string $role;
 public function __construct(
 string $id, string $name, string $email,
 string $password, string $role
 ) {
 $this->id = trim($id);
 $this->name = trim($name);
 $this->email = trim($email);
 $this->password = trim($password);
 $this->role = trim($role);
 }
 public function getId(): string { return $this->id; }
 public function getName(): string { return $this->name; }
 public function getEmail(): string { return $this->email; }
 public function getPassword(): string { return $this->password; }
 public function getRole(): string { return $this->role; }
}
