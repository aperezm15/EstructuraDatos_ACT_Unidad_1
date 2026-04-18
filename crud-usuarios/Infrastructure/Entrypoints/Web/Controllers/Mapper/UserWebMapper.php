<?php

declare(strict_types=1);

final class UserWebMapper
{
 
    public function fromCreateRequestToCommand(CreateUserWebRequest $request): CreateUserCommand
{
    $generateId = bin2hex(random_bytes(16));
    return new CreateUserCommand(
        $generateId,
        $request->getName(),     
        $request->getEmail(),
        $request->getPassword(),
        $request->getRole()             
    );
}


    public function mapResponse(UserModel $user): UserResponse
    {
        return new UserResponse(
            $user->id()->value(),
            $user->name()->value(),
            $user->email()->value(),
            $user->role(),
            $user->status()
        );
    }

 
    public function mapCollection(array $users): array
    {
        return array_map(fn(UserModel $user) => $this->mapResponse($user), $users);
    }

    public function fromModelToResponse(UserModel $user): UserResponse
{
    return new UserResponse(
        $user->Id()->value(),
        $user->Name()->value(),
        $user->Email()->value(),
        $user->Role(),
        $user->Status()
    );
}
}