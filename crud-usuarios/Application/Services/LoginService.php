<?php
declare(strict_types=1);

final class LoginService implements LoginUseCase
{
private GetUserByEmailPort $getUserByEmailPort;
public function __construct(GetUserByEmailPort $getUserByEmailPort)
{
$this->getUserByEmailPort = $getUserByEmailPort;
}
public function execute(LoginCommand $command): UserModel
{
$email = new UserEmail($command->getEmail());
$user = $this->getUserByEmailPort->getByEmail($email);
if ($user === null || !$user->password()->verify($command->getPassword())) {
throw InvalidCredentialsException::becauseCredentialsAreInvalid();
}
if ($user->status() !== UserStatusEnum::ACTIVE) {
throw InvalidCredentialsException::becauseUserIsNotActive();
}
return $user;
}
}
