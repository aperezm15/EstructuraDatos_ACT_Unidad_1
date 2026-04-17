<?php
declare(strict_types=1);
require_once __DIR__ . '/Mapper/UserWebMapper.php';
require_once __DIR__ . '/../../../../Application/Ports/In/CreateUserUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/UpdateUserUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/GetUserByIdUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/GetAllUsersUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/DeleteUserUseCase.php';
require_once __DIR__ . '/../../../../Application/Services/Dto/Queries/GetAllUsersQuery.php';
final class UserController
{
 private CreateUserUseCase $createUserUseCase;
 private UpdateUserUseCase $updateUserUseCase;
 private GetUserByIdUseCase $getUserByIdUseCase;
 private GetAllUsersUseCase $getAllUsersUseCase;
 private DeleteUserUseCase $deleteUserUseCase;
 private UserWebMapper $mapper;
 public function __construct(
 CreateUserUseCase $createUserUseCase,
 UpdateUserUseCase $updateUserUseCase,
 GetUserByIdUseCase $getUserByIdUseCase,
 GetAllUsersUseCase $getAllUsersUseCase,
 DeleteUserUseCase $deleteUserUseCase,
 UserWebMapper $mapper
 ) {
 $this->createUserUseCase = $createUserUseCase;
 $this->updateUserUseCase = $updateUserUseCase;
 $this->getUserByIdUseCase = $getUserByIdUseCase;
 $this->getAllUsersUseCase = $getAllUsersUseCase;
 $this->deleteUserUseCase = $deleteUserUseCase;
 $this->mapper = $mapper;
 }
 /**
 * @return UserResponse[]
 */
 public function index(): array
 {
 $users = $this->getAllUsersUseCase->execute(new GetAllUsersQuery());
 return $this->mapper->fromModelsToResponses($users);
 }
 public function show(string $id): UserResponse
 {
 $query = $this->mapper->fromIdToGetByIdQuery($id);
 $user = $this->getUserByIdUseCase->execute($query);
 return $this->mapper->fromModelToResponse($user);
 }
 public function store(CreateUserWebRequest $request): UserResponse
 {
    $command = $this->mapper->fromCreateRequestToCommand($request);
 $user = $this->createUserUseCase->execute($command);
 return $this->mapper->fromModelToResponse($user);
 }
 public function update(UpdateUserWebRequest $request): UserResponse
 {
 $command = $this->mapper->fromUpdateRequestToCommand($request);
 $user = $this->updateUserUseCase->execute($command);
 return $this->mapper->fromModelToResponse($user);
 }
 public function delete(string $id): void
 {
 $command = $this->mapper->fromIdToDeleteCommand($id);
 $this->deleteUserUseCase->execute($command);
 }
}
