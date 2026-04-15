<?php

class InvalidUserStatusException extends InvalidArgumentException {
    public static function becauseValueIsInvalid($value) {
        return new self("El estado ". $value ." no es valido");
    } 
}