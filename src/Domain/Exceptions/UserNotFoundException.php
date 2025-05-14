<?php

namespace Laymont\HexagonalArchitecture\Domain\Exceptions;

class UserNotFoundException extends DomainException
{
    public function __construct(string $message = "Usuario no encontrado", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
