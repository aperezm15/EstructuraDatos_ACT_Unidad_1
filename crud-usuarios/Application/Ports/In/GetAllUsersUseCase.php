<?php
declare(strict_types=1);
require_once __DIR__ . '/../../Services/Dto/Queries/GetAllUsersQuery.php';
require_once __DIR__ . '/../../../Domain/Models/UserModel.php';
interface GetAllUsersUseCase
{
 /** @return UserModel[] */
 public function execute(GetAllUsersQuery $query): array;
}
