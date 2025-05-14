<?php

namespace Laymont\HexagonalArchitecture\Domain\Exceptions;

class InvalidCredentialsException extends DomainException
{
    public function __construct(string $message = "Credenciales inválidas", int $code = 401, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
