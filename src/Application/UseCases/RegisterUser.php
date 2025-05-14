<?php
namespace Laymont\HexagonalArchitecture\Application\UseCases;

use Laymont\HexagonalArchitecture\Application\Ports\Inbound\UserServiceInterface;
use Laymont\HexagonalArchitecture\Application\Ports\Outbound\UserRepositoryInterface;
use Laymont\HexagonalArchitecture\Domain\Entities\User;
use Laymont\HexagonalArchitecture\Domain\Exceptions\DomainException;

class RegisterUser implements UserServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function register(array $data): User
    {
        // Validaciones básicas
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            throw new DomainException('Todos los campos son obligatorios (name, email, password)');
        }
        
        // Aquí puedes agregar lógica de negocio adicional si lo necesitas
        return $this->repository->save($data);
    }
    
    // Implementar métodos requeridos por la interfaz
    public function login(array $credentials): ?User
    {
        throw new DomainException('Método no implementado en este caso de uso. Utilice LoginUser.');
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
