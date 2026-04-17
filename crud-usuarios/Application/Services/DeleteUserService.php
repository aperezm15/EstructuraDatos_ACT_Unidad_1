<?php

declare(strict_types=1);
require_once __DIR__ . '/../Ports/In/DeleteUserUseCase.php';
require_once __DIR__ . '/../Ports/Out/DeleteUserPort.php';
require_once __DIR__ . '/../Ports/Out/GetUserByIdPort.php';
require_once __DIR__ . '/Mappers/UserApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/UserNotFoundException.php';
final class DeleteUserService implements DeleteUserUseCase
{
 private DeleteUserPort $deleteUserPort;
 private GetUserByIdPort $getUserByIdPort;
 public function __construct(
 DeleteUserPort $deleteUserPort,
 GetUserByIdPort $getUserByIdPort
 ) {
 $this->deleteUserPort = $deleteUserPort;
 $this->getUserByIdPort = $getUserByIdPort;
 }
 public function execute(DeleteUserCommand $command): void
 {
 $userId = UserApplicationMapper::fromDeleteCommandToUserId($command);
 $existingUser = $this->getUserByIdPort->getById($userId);
 if ($existingUser === null) {
 throw UserNotFoundException::becauseIdWasNotFound($userId->value());
 }
 $this->deleteUserPort->delete($userId);
 }
}
