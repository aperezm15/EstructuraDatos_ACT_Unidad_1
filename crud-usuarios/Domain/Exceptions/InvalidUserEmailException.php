<?php

class InvalidUserEmailException extends InvalidArgumentException {


    public static function becauseFormatIsInvalid($email) {
        return new static("El formato del email es invqalido: " . $email);
    }
    public static function becauseValueIsEmpty() {
        return new self("El nombre del usuario no puede esta vacio");
    }
}