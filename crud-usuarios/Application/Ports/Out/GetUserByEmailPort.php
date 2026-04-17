<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../Domain/Models/UserModel.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/UserEmail.php';
interface GetUserByEmailPort
{
 public function getByEmail(UserEmail $email): ?UserModel;
}
