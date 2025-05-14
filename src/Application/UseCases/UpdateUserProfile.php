<?php
namespace Laymont\HexagonalArchitecture\Application\UseCases;

use Laymont\HexagonalArchitecture\Application\Ports\Inbound\UserServiceInterface;
use Laymont\HexagonalArchitecture\Application\Ports\Outbound\UserRepositoryInterface;
use Laymont\HexagonalArchitecture\Domain\Entities\User;
use Laymont\HexagonalArchitecture\Domain\Exceptions\DomainException;
use Laymont\HexagonalArchitecture\Domain\Exceptions\UserNotFoundException;

class UpdateUserProfile implements UserServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function updateProfile(int $userId, array $data): User
    {
        // Validar que hay campos para actualizar
        if (empty($data)) {
            throw new DomainException('No se proporcionaron campos para actualizar');
        }
        
        // La validación de existencia del usuario se hace en el repositorio
        return $this->repository->update($userId, $data);
    }

    // Implementar métodos requeridos por la interfaz
    public function register(array $data): User
    {
        throw new DomainException('Método no implementado en este caso de uso. Utilice RegisterUser.');
    }
    
    public function login(array $credentials): ?User
    {
        throw new DomainException('Método no implementado en este caso de uso. Utilice LoginUser.');
    }
    
    public function softDelete(int $userId): bool
    {
        throw new DomainException('Método no implementado en este caso de uso. Utilice DeleteUser.');
    }
}
