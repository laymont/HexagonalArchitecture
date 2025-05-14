<?php
namespace Laymont\HexagonalArchitecture\Application\UseCases;

use Laymont\HexagonalArchitecture\Application\Ports\Inbound\UserServiceInterface;
use Laymont\HexagonalArchitecture\Application\Ports\Outbound\UserRepositoryInterface;
use Laymont\HexagonalArchitecture\Domain\Entities\User;
use Laymont\HexagonalArchitecture\Domain\Exceptions\DomainException;
use Laymont\HexagonalArchitecture\Domain\Exceptions\InvalidCredentialsException;

class LoginUser implements UserServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function login(array $credentials): ?User
    {
        // Validar que se proporcionaron las credenciales necesarias
        if (empty($credentials['email']) || empty($credentials['password'])) {
            throw new InvalidCredentialsException('El email y la clave son obligatorios');
        }
        
        return $this->repository->findByCredentials($credentials);
    }

    // Implementar métodos requeridos por la interfaz
    public function register(array $data): User
    {
        throw new DomainException('Método no implementado en este caso de uso. Utilice RegisterUser.');
    }
    
    public function updateProfile(int $userId, array $data): User
    {
        throw new DomainException('Método no implementado en este caso de uso. Utilice UpdateUserProfile.');
    }
    
    public function softDelete(int $userId): bool
    {
        throw new DomainException('Método no implementado en este caso de uso. Utilice DeleteUser.');
    }
}
