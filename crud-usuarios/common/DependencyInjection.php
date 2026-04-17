<?php
declare(strict_types=1);
require_once __DIR__ . '/ClassLoader.php';
final class DependencyInjection
{
 public static function boot(): void
 {
 ClassLoader::register();
 }
 public static function getConnection(): Connection
 {
 ClassLoader::loadClass('Connection');
 return new Connection(
 host: '127.0.0.1',
 port: 3306,
 database: 'software_web_db',
 username: 'root',
 password: '123456',
 charset: 'utf8mb4'
 );
 }
 public static function getPdo(): PDO
 {
 return self::getConnection()->createPdo();
 }
 public static function getUserPersistenceMapper(): UserPersistenceMapper
 {
 ClassLoader::loadClass('UserPersistenceMapper');
 return new UserPersistenceMapper();
 }
 public static function getUserRepository(): UserRepositoryMySQL
 {
 ClassLoader::loadClass('UserRepositoryMySQL');
 return new UserRepositoryMySQL(self::getPdo(), self::getUserPersistenceMapper());
 }
 public static function getCreateUserUseCase(): CreateUserUseCase
 {
 ClassLoader::loadClass('CreateUserService');
 $repo = self::getUserRepository();
 return new CreateUserService($repo, $repo);
 }
 public static function getUpdateUserUseCase(): UpdateUserUseCase
 {
 ClassLoader::loadClass('UpdateUserService');
 $repo = self::getUserRepository();
 return new UpdateUserService($repo, $repo, $repo);
 }
 public static function getDeleteUserUseCase(): DeleteUserUseCase
 {
 ClassLoader::loadClass('DeleteUserService');
 $repo = self::getUserRepository();
 return new DeleteUserService($repo, $repo);
 }
 public static function getGetUserByIdUseCase(): GetUserByIdUseCase
 {
 ClassLoader::loadClass('GetUserByIdService');
 return new GetUserByIdService(self::getUserRepository());
 }
 public static function getGetAllUsersUseCase(): GetAllUsersUseCase
 {
 ClassLoader::loadClass('GetAllUsersService');
 return new GetAllUsersService(self::getUserRepository());
 }
 public static function getUserWebMapper(): UserWebMapper
 {
 ClassLoader::loadClass('UserWebMapper');
 return new UserWebMapper();
 }
 public static function getUserController(): UserController
 {
 ClassLoader::loadClass('UserController');
 return new UserController(
 self::getCreateUserUseCase(),
 self::getUpdateUserUseCase(),
 self::getGetUserByIdUseCase(),
 self::getGetAllUsersUseCase(),
 self::getDeleteUserUseCase(),
 self::getUserWebMapper()
 );
 }
}
