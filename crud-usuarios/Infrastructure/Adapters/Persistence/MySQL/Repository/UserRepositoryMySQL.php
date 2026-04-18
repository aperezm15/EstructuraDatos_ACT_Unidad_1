<?php

declare(strict_types=1);
require_once __DIR__ . '/../../../../../Application/Ports/Out/SaveUserPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/UpdateUserPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/GetUserByIdPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/GetUserByEmailPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/GetAllUsersPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/DeleteUserPort.php';
require_once __DIR__ . '/../Mapper/UserPersistenceMapper.php';
require_once __DIR__ . '/../../../../../Domain/Models/UserModel.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/UserId.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/UserEmail.php';
final class UserRepositoryMySQL implements
    SaveUserPort,
    UpdateUserPort,
    GetUserByIdPort,
    GetUserByEmailPort,
    GetAllUsersPort,
    DeleteUserPort
{
    private PDO $pdo;
    private UserPersistenceMapper $mapper;
    public function __construct(PDO $pdo, UserPersistenceMapper $mapper)
    {
        $this->pdo = $pdo;
        $this->mapper = $mapper;
    }
    public function save(UserModel $user): UserModel
    {
        $dto = $this->mapper->fromModelToDto($user);
        $sql = '
INSERT INTO users (
id,
name,
email,
password,
role,
status,
created_at,
updated_at
) VALUES (
:id,
:name,
:email,
:password,
:role,
:status,
NOW(),
NOW()
)
';
        $statement = $this->pdo->prepare($sql);
        $statement->execute(array(
            ':id' => $dto->id(),
            ':name' => $dto->name(),
            ':email' => $dto->email(),
            ':password' => $dto->password(),
            ':role' => $dto->role(),
            ':status' => $dto->status(),
        ));
        $savedUser = $this->getById(new UserId($dto->id()));
        if ($savedUser === null) {
            throw new RuntimeException('The user could not be recovered after save.');
        }
        return $savedUser;
    }
    public function update(UserModel $user): UserModel
    {
        $dto = $this->mapper->fromModelToDto($user);
        $sql = '
UPDATE users
SET name = :name,
email = :email,
password = :password,
role = :role,
status = :status,
updated_at = NOW()
WHERE id = :id
';
        $statement = $this->pdo->prepare($sql);
        $statement->execute(array(
            ':id' => $dto->id(),
            ':name' => $dto->name(),
            ':email' => $dto->email(),
            ':password' => $dto->password(),
            ':role' => $dto->role(),
            ':status' => $dto->status(),
        ));
        $updatedUser = $this->getById(new UserId($dto->id()));
        if ($updatedUser === null) {
            throw new RuntimeException('The user could not be recovered after update.');
        }
        return $updatedUser;
    }
    public function getById(UserId $userId): ?UserModel
    {
        $sql = '
SELECT
id,
name,
email,
password,
role,
status,
created_at,
updated_at
FROM users
WHERE id = :id
LIMIT 1
';
        $statement = $this->pdo->prepare($sql);
        $statement->execute(array(
            ':id' => $userId->value(),
        ));
        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }
        return $this->mapper->fromRowToModel($row);
    }
    public function getByEmail(UserEmail $email): ?UserModel
    {
        $sql = '
SELECT
id,
name,
email,
password,
role,
status,
created_at,
updated_at
FROM users
WHERE email = :email
LIMIT 1
';
        $statement = $this->pdo->prepare($sql);
        $statement->execute(array(
            ':email' => $email->value(),
        ));
        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }
        return $this->mapper->fromRowToModel($row);
    }
    /**
     * @return UserModel[]
     */
    public function getAll(): array
    {
        $sql = '
SELECT
id,
name,
email,
password,
role,
status,
created_at,
updated_at
FROM users
ORDER BY name ASC
';
        $statement = $this->pdo->query($sql);
        $rows = $statement->fetchAll();
        return $this->mapper->fromRowsToModels($rows);
    }
    public function delete(UserId $userId): void
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->execute(array(
            ':id' => $userId->value(),
        ));
    }

    public function saveWithToken(UserModel $user, string $token): void
    {
        $sql = "INSERT INTO users (id, name, email, password, role, status, activation_token) 
            VALUES (:id, :name, :email, :password, :role, :status, :token)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':id', $user->id()->value());
        $stmt->bindValue(':name', $user->name()->value());
        $stmt->bindValue(':email', $user->email()->value());
        $stmt->bindValue(':password', $user->password()->value());
        $stmt->bindValue(':role', $user->role());
        $stmt->bindValue(':status', $user->status());
        $stmt->bindValue(':token', $token);

        $stmt->execute();
    }

    public function activateByToken(string $token): bool
{
    // 1. Buscamos si existe un usuario con ese token
    // 2. Si existe, cambiamos su status a 'ACTIVE' y borramos el token para que no se use dos veces
    $sql = "UPDATE users 
            SET status = 'ACTIVE', activation_token = NULL 
            WHERE activation_token = :token 
            LIMIT 1";

    $stmt = $this->pdo->prepare($sql); 
    $stmt->bindValue(':token', $token);
    $stmt->execute();

    // Retornamos true si se modificó alguna fila, false si el token no existía
    return $stmt->rowCount() > 0;
}
}
