<?php

require_once __DIR__ ."/../Exceptions/InvalidUserNameException.php";

class UserName {

    private $value;

    public function __construct($value) {
        $normalized = trim((string) $value);

        if ($normalized === "") {
            throw InvalidUserNameException::becauseValueIsEmpty();
        }

        if (mb_strlen($normalized) < 3) {
            throw InvalidUserNameException::becauseLengthIsTooShort(3);
        }

        $this->value = $normalized;
    }

    public function value() {
        return $this->value;
    }

    public function equals(UserName $other) {
        return $this->value() === $other->value();
    }

    public function __toString() {
        return $this->value();
    }
}