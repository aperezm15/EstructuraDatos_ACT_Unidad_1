<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../Domain/Models/UserModel.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/UserId.php';
interface GetUserByIdPort
{
 public function getById(UserId $Id): ?UserModel;
}
