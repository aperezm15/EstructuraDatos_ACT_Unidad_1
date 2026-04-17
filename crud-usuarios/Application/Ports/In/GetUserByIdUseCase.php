<?php
declare(strict_types=1);
require_once __DIR__ . '/../../Services/Dto/Queries/GetUserByIdQuery.php';
require_once __DIR__ . '/../../../Domain/Models/UserModel.php';
interface GetUserByIdUseCase
{
 public function execute(GetUserByIdQuery $query): UserModel;
}
