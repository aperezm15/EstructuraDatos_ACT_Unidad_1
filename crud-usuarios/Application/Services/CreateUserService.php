<?php

declare(strict_types=1);

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

        // 1. Generamos el token de activación
        $activationToken = bin2hex(random_bytes(32));

        // 2. Guardamos al usuario y el token en la base de datos
        $this->saveUserPort->saveWithToken($user, $activationToken);

        // 3. Preparamos el link de activación
        $activationLink = "http://localhost/DesarrolloWeb_ACT_Unidad_1/crud-usuarios/public/index.php?route=activate&token=" . $activationToken;
        
        // 4. Definimos el asunto
        $subject = "Activa tu cuenta, " . $user->name()->value();
        
        /* MODIFICACIÓN AQUÍ: 
           Pasamos directamente el link como body. 
           Nuestro PhpMailAdapter lo tomará y lo pondrá dentro del botón <a href="...">
        */
        $this->sendEmailPort->send(
            $user->email()->value(), 
            $subject, 
            $activationLink
        );

        return $user;
    }
}