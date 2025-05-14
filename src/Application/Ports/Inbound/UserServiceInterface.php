<?php
namespace Laymont\HexagonalArchitecture\Application\Ports\Inbound;

use Laymont\HexagonalArchitecture\Domain\Entities\User;

interface UserServiceInterface
{
    public function register(array $data): User;
    public function login(array $credentials): ?User;
    public function updateProfile(int $userId, array $data): User;
    public function softDelete(int $userId): bool;
}
