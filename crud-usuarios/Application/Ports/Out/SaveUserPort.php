<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/Models/UserModel.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/UserId.php';

interface SaveUserPort
{
    // Método para registro normal
    public function save(UserModel $user): UserModel;

    // Método para registro con activación por email
    public function saveWithToken(UserModel $user, string $token): void;

    // Método para recuperación de contraseña
    public function updatePassword(UserId $id, string $hashedPassword): void;
}