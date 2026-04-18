<?php

declare(strict_types=1);
require_once __DIR__ . '/../Ports/In/CreateUserUseCase.php';
require_once __DIR__ . '/../Ports/Out/SaveUserPort.php';
require_once __DIR__ . '/../Ports/Out/GetUserByEmailPort.php';
require_once __DIR__ . '/Mappers/UserApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/UserAlreadyExistsException.php';
require_once __DIR__ . '/../../Domain/ValueObjects/UserEmail.php';
require_once __DIR__ . '/../Ports/Out/SendEmailPort.php';
final class CreateUserService implements CreateUserUseCase
{
    private SaveUserPort $saveUserPort;
    private GetUserByEmailPort $getUserByEmailPort;
    private SendEmailPort $sendEmailPort;
    public function __construct(
        SaveUserPort $saveUserPort,
        GetUserByEmailPort $getUserByEmailPort,
        SendEmailPort $sendEmailPort
    ) {
        $this->saveUserPort = $saveUserPort;
        $this->getUserByEmailPort = $getUserByEmailPort;
        $this->sendEmailPort = $sendEmailPort; 
    }
    public function execute(CreateUserCommand $command): UserModel
    {
        $email = new UserEmail($command->getEmail());
        $existingUser = $this->getUserByEmailPort->getByEmail($email);
        
        if ($existingUser !== null) {
            throw UserAlreadyExistsException::becauseEmailAlreadyExists($email->value());
        }

        $user = UserApplicationMapper::fromCreateCommandToModel($command);

        // 1. Generamos el token
        $activationToken = bin2hex(random_bytes(32));

        // 2. Guardamos usando el método del TOKEN (quitamos el return de arriba)
        $this->saveUserPort->saveWithToken($user, $activationToken);

        // 3. Preparamos y enviamos el correo
        $activationLink = "http://localhost/DesarrolloWeb_ACT_Unidad_1/crud-usuarios/public/index.php?route=activate&token=" . $activationToken;
        $subject = "Activa tu cuenta, " . $user->name()->value();
        $body = "Hola! Por favor activa tu cuenta haciendo clic en el siguiente enlace: " . $activationLink;

        $this->sendEmailPort->send($user->email()->value(), $subject, $body);

        // 4. AHORA SÍ, retornamos al final
        return $user;
    }

    
}
