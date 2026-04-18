<?php

declare(strict_types=1);

final class UpdateUserService implements UpdateUserUseCase
{
    private UpdateUserPort $updateUserPort;
    private GetUserByIdPort $getUserByIdPort;
    private GetUserByEmailPort $getUserByEmailPort;

    public function __construct(
        UpdateUserPort $updateUserPort,
        GetUserByIdPort $getUserByIdPort,
        GetUserByEmailPort $getUserByEmailPort
    ) {
        $this->updateUserPort = $updateUserPort;
        $this->getUserByIdPort = $getUserByIdPort;
        $this->getUserByEmailPort = $getUserByEmailPort;
    }

    public function execute(UpdateUserCommand $command): UserModel
    {
        $userId = new UserId($command->getId());
        
        // 1. Verificar que el usuario a editar realmente existe
        $currentUser = $this->getUserByIdPort->getById($userId);
        if ($currentUser === null) {
            throw UserNotFoundException::becauseIdWasNotFound($userId->value());
        }

        // 2. Validar duplicidad de Email
        $newEmail = new UserEmail($command->getEmail());
        $userWithSameEmail = $this->getUserByEmailPort->getByEmail($newEmail);

        // Si el email ya existe y NO pertenece al usuario que estamos editando...
        if ($userWithSameEmail !== null && !$userWithSameEmail->id()->equals($userId)) {
            throw UserAlreadyExistsException::becauseEmailAlreadyExists($newEmail->value());
        }

        // 3. Gestión de la contraseña
        // Si el campo viene vacío en el formulario, mantenemos el hash que ya tenemos en BD
        $password = ($command->getPassword() !== '')
            ? UserPassword::fromPlainText($command->getPassword())
            : $currentUser->password();

        // 4. Mapear al modelo de dominio
        $userToUpdate = new UserModel(
            $userId,
            new UserName($command->getName()),
            $newEmail,
            $password,
            $command->getRole(),
            $command->getStatus()
        );

        // 5. Persistir cambios
        return $this->updateUserPort->update($userToUpdate);
    }
}