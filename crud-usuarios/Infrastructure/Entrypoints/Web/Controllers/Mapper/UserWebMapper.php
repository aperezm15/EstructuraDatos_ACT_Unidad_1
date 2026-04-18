<?php

declare(strict_types=1);

final class UserWebMapper
{
    public function fromCreateRequestToCommand(CreateUserWebRequest $request): CreateUserCommand
    {
        return new CreateUserCommand(
            $request->getId(), // Usamos el ID que viene del Controller/Form
            $request->getName(),
            $request->getEmail(),
            $request->getPassword(),
            $request->getRole()
        );
    }

    public function fromUpdateRequestToCommand(UpdateUserWebRequest $request): UpdateUserCommand
    {
        return new UpdateUserCommand(
            $request->getId(),
            $request->getName(),
            $request->getEmail(),
            $request->getPassword(),
            $request->getRole(),
            $request->getStatus()
        );
    }

    public function fromIdToGetByIdQuery(string $id): GetUserByIdQuery
    {
        return new GetUserByIdQuery($id);
    }

    public function fromIdToDeleteCommand(string $id): DeleteUserCommand
    {
        return new DeleteUserCommand($id);
    }

    public function fromModelToResponse(UserModel $user): UserResponse
    {
        return new UserResponse(
            $user->id()->value(),
            $user->name()->value(),
            $user->email()->value(),
            $user->role(),
            $user->status()
        );
    }

    public function fromModelsToResponses(array $users): array
    {
        return array_map(
            fn(UserModel $user) => $this->fromModelToResponse($user),
            $users
        );
    }
}