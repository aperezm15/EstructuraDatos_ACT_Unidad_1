<?php

declare(strict_types=1);
require_once __DIR__ . '/../Ports/In/GetAllUsersUseCase.php';
require_once __DIR__ . '/../Ports/Out/GetAllUsersPort.php';
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
