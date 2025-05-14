<?php
namespace Laymont\HexagonalArchitecture\Application\Ports\Outbound;

use Laymont\HexagonalArchitecture\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function save(array $data): User;
    public function findByCredentials(array $credentials): ?User;
    public function update(int $id, array $data): User;
    public function delete(int $id, bool $softDelete = true): bool;
}
