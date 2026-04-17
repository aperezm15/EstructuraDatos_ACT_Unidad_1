<?php

class InvalidCredentialsException extends InvalidArgumentException
{
    public static function becauseCredentialsAreInvalid(): self
    {
        return new self("El correo electrónico o la contraseña son incorrectos.");
    }

    public static function becauseUserIsNotActive(): self
    {
        return new self("Tu cuenta no se encuentra activa. Contacta al administrador.");
    }
}