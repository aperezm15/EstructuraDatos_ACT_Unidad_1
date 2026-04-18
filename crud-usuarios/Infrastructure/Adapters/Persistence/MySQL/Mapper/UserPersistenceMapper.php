<?php

declare(strict_types=1);

final class UserPersistenceMapper
{
    public function fromModelToDto(UserModel $user): UserPersistenceDto
    {
        return new UserPersistenceDto(
            $user->id()->value(),
            $user->name()->value(),
            $user->email()->value(),
            $user->password()->value(),
            $user->role(),
            $user->status()
        );
    }
    public function fromDtoToEntity(UserPersistenceDto $dto): UserEntity
    {
        return new UserEntity(
            $dto->id(),
            $dto->name(),
            $dto->email(),
            $dto->password(),
            $dto->role(),
            $dto->status()
        );
    }
    public function fromRowToEntity(array $row): UserEntity
    {
        return new UserEntity(
            (string) $row['id'],
            (string) $row['name'],
            (string) $row['email'],
            (string) $row['password'],
            (string) $row['role'],
            (string) $row['status'],
            isset($row['created_at']) ? (string) $row['created_at'] : null,
            isset($row['updated_at']) ? (string) $row['updated_at'] : null
        );
    }
    public function fromEntityToModel(UserEntity $entity): UserModel
    {
        return new UserModel(
            new UserId($entity->id()),
            new UserName($entity->name()),
            new UserEmail($entity->email()),
            UserPassword::fromHash($entity->password()),
            $entity->role(),
            $entity->status()
        );
    }
    public function fromRowToModel(array $row): UserModel
    {
        return $this->fromEntityToModel(
            $this->fromRowToEntity($row)
        );
    }
    /**
     * @param array<int, array<string, mixed>> $rows
     * @return UserModel[]
     */
    public function fromRowsToModels(array $rows): array
    {
        $models = array();
        foreach ($rows as $row) {
            $models[] = $this->fromRowToModel($row);
        }
        return $models;
    }
}
