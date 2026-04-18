<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../Domain/Models/UserModel.php';
interface SaveUserPort
{
 public function save(UserModel $user): UserModel; #No se esta utilizando, lo cambie por saveWithToken
 public function detele(UserId $id): void;
 public function update(UserModel $user): void;

 public function saveWithToken(UserModel $user, string $token): void;

 public function updatePassword(UserId $id, string $hashedPassword): void;
}
