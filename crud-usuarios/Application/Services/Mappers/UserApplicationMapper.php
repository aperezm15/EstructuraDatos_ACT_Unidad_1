<?php

declare(strict_types=1);

final class UserApplicationMapper
{
    public static function fromCreateCommandToModel(CreateUserCommand $command): UserModel
    {
        return new UserModel(
            new UserId($command->getId()),
            new UserName($command->getName()),
            new UserEmail($command->getEmail()),
            UserPassword::fromPlainText($command->getPassword()),
            $command->getRole(),
            UserStatusEnum::PENDING
        );
    }
    public static function fromUpdateCommandToModel(UpdateUserCommand $command): UserModel
    {
        return new UserModel(
            new UserId($command->getId()),
            new UserName($command->getName()),
            new UserEmail($command->getEmail()),
            UserPassword::fromPlainText($command->getPassword()),
            $command->getRole(),
            $command->getStatus()
        );
    }
    public static function fromGetUserByIdQueryToUserId(GetUserByIdQuery $query): UserId
    {
        return new UserId($query->Id());
    }
    public static function fromDeleteCommandToUserId(DeleteUserCommand $command): UserId
    {
        return new UserId($command->getId());
    }
    /**
     * @return array<string, string>
     */
    public static function fromModelToArray(UserModel $user): array
    {
        return array(
            'id' => $user->id()->value(),
            'name' => $user->name()->value(),
            'email' => $user->email()->value(),
            'password' => $user->password()->value(),
            'role' => $user->role(),
            'status' => $user->status()
        );
    }
    /**
     * @param UserModel[] $users
     * @return array<int, array<string, string>>
     */
    public static function fromModelsToArray(array $users): array
    {
        $result = array();
        foreach ($users as $user) {
            $result[] = self::fromModelToArray($user);
        }
        return $result;
    }
}
