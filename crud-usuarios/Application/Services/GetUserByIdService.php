<?php

declare(strict_types=1);

final class GetUserByIdService implements GetUserByIdUseCase
{
    private GetUserByIdPort $getUserByIdPort;
    public function __construct(GetUserByIdPort $getUserByIdPort)
    {
        $this->getUserByIdPort = $getUserByIdPort;
    }
    public function execute(GetUserByIdQuery $query): UserModel
    {
        $userId = UserApplicationMapper::fromGetUserByIdQueryToUserId($query);
        $user = $this->getUserByIdPort->getById($userId);
        if ($user === null) {
            throw UserNotFoundException::becauseIdWasNotFound($userId->value());
        }
        return $user;
    }
}
