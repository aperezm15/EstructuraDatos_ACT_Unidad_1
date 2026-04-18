<?php

declare(strict_types=1);

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
        // Transformamos el comando en un Value Object UserId
        $userId = UserApplicationMapper::fromDeleteCommandToUserId($command);

        // Verificamos si el usuario existe antes de intentar borrarlo
        $existingUser = $this->getUserByIdPort->getById($userId);

        if ($existingUser === null) {
            throw UserNotFoundException::becauseIdWasNotFound($userId->value());
        }

        // Ejecutamos la eliminación en el puerto (repositorio)
        $this->deleteUserPort->delete($userId);
    }
}