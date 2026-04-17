<?php

require_once __DIR__ . '/../Exceptions/InvalidUserPasswordException.php';

class UserPassword
{
    private $value;

    // Cambiamos el constructor a privado para que solo se pueda crear
    // una contraseña a través de los métodos estáticos (Named Constructors)
    private function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Este es el método que te pide el UserApplicationMapper
     * Se encarga de validar, limpiar y ENCRIPTAR la contraseña
     */
    public static function fromPlainText($plainText)
    {
        $normalized = trim((string) $plainText);

        if ($normalized === '') {
            throw InvalidUserPasswordException::becauseValueIsEmpty();
        }

        if (strlen($normalized) < 8) {
            throw InvalidUserPasswordException::becauseLengthIsTooShort(8);
        }

        // Encriptamos la contraseña usando el algoritmo estándar de PHP (Bcrypt)
        // Esto transforma "12345678" en algo como "$2y$10$asdf..."
        $hashedPassword = password_hash($normalized, PASSWORD_BCRYPT);

        return new self($hashedPassword);
    }

    /**
     * Este método se usa cuando cargamos la contraseña YA ENCRIPTADA 
     * desde la base de datos (se verá en la Guía 04)
     */
    public static function fromValue($hashedValue)
    {
        return new self($hashedValue);
    }

    public function value()
    {
        return $this->value;
    }

    /**
     * Verifica si una contraseña escrita por el usuario coincide con el hash guardado
     * (Se usará en el Login)
     */
    public function verify($plainText)
    {
        return password_verify($plainText, $this->value);
    }

    public function equals(UserPassword $other)
    {
        return $this->value === $other->value();
    }

    public function __toString()
    {
        return $this->value;
    }
}