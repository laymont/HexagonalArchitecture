<?php
namespace Laymont\HexagonalArchitecture\Domain\ValueObjects;
readonly class AdditionalUserInfo
{
    public function __construct(
        public array $fields
    ) {}
}
