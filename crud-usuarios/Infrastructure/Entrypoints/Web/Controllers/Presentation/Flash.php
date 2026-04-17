<?php

declare(strict_types=1);
final class Flash
{
    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION['_flash'][$key] = $value;
    }
    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        if (!isset($_SESSION['_flash'][$key])) {
            return $default;
        }
        $value = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);
        return $value;
    }
    /**
     * @param array<string, string> $data
     */
    public static function setOld(array $data): void
    {
        self::set('old', $data);
    }
    /**
     * @return array<string, string>
     */
    public static function old(): array
    {
        $data = self::get('old', array());
        return is_array($data) ? $data : array();
    }
    /**
     * @param array<string, string> $errors
     */
    public static function setErrors(array $errors): void
    {
        self::set('errors', $errors);
    }
    /**
     * @return array<string, string>
     */
    public static function errors(): array
    {
        $errors = self::get('errors', array());
        return is_array($errors) ? $errors : array();
    }
    public static function setMessage(string $message): void
    {
        self::set('message', $message);
    }
    public static function message(): string
    {
        $message = self::get('message', '');
        return is_string($message) ? $message : '';
    }
    public static function setSuccess(string $message): void
    {
        self::set('success', $message);
    }
    public static function success(): string
    {
        $message = self::get('success', '');
        return is_string($message) ? $message : '';
    }
}
