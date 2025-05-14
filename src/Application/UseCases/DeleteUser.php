<?php
namespace Laymont\HexagonalArchitecture\Application\UseCases;

use Laymont\HexagonalArchitecture\Application\Ports\Inbound\UserServiceInterface;
use Laymont\HexagonalArchitecture\Application\Ports\Outbound\UserRepositoryInterface;
use Laymont\HexagonalArchitecture\Domain\Entities\User;
use Laymont\HexagonalArchitecture\Domain\Exceptions\DomainException;

class DeleteUser implements UserServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function softDelete(int $userId): bool
    {
        return $this->repository->delete($userId, true);
    }

    // Implementar métodos requeridos por la interfaz

    /**
     * @throws DomainException
     */
    public function register(array $data): User
    {
        throw new DomainException('Método no implementado en este caso de uso. Utilice RegisterUser.');
    }

    /**
     * @throws DomainException
     */
    public function login(array $credentials): ?User
    {
        throw new DomainException('Método no implementado en este caso de uso. Utilice LoginUser.');
    }

    /**
     * @throws DomainException
     */
    public function updateProfile(int $userId, array $data): User
    {
        throw new DomainException('Método no implementado en este caso de uso. Utilice UpdateUserProfile.');
    }
}
