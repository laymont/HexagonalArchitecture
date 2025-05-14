<?php

namespace Laymont\HexagonalArchitecture\Domain\Exceptions;

use Exception;

class DomainException extends Exception
{
    public function __construct(string $message = "Error de dominio", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
