<?php

declare(strict_types=1);
final class GetAllUsersService implements GetAllUsersUseCase
{
    private GetAllUsersPort $getAllUsersPort;
    public function __construct(GetAllUsersPort $getAllUsersPort)
    {
        $this->getAllUsersPort = $getAllUsersPort;
    }
    /** @return UserModel[] */
    public function execute(GetAllUsersQuery $query): array
    {
        return $this->getAllUsersPort->getAll();
    }
}
