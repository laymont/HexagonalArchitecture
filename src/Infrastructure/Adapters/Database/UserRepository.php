<?php
namespace Laymont\HexagonalArchitecture\Infrastructure\Adapters\Database;

use Laymont\HexagonalArchitecture\Application\Ports\Outbound\UserRepositoryInterface;
use Laymont\HexagonalArchitecture\Domain\Entities\User;
use Laymont\HexagonalArchitecture\Domain\Exceptions\DomainException;
use Laymont\HexagonalArchitecture\Domain\Exceptions\UserNotFoundException;
use Laymont\HexagonalArchitecture\Domain\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @throws DomainException
     */
    public function save(array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Extraer datos adicionales que no pertenecen a la tabla users
        $extraData = array_diff_key($data, array_flip(['name', 'email', 'password']));

        try {
            DB::beginTransaction();

            $id = DB::table('users')->insertGetId($userData);

            DB::commit();

            return new User(
                id: $id,
                name: $userData['name'],
                email: $userData['email'],
                password: $userData['password'],
                extra: $extraData
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException('Error al guardar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function findByCredentials(array $credentials): ?User
    {
        $email = $credentials['email'] ?? null;
        $password = $credentials['password'] ?? null;

        if (!$email || !$password) {
            throw new InvalidCredentialsException('Email y password son obligatorios');
        }

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $authUser = Auth::user();

            return new User(
                id: $authUser->id,
                name: $authUser->name,
                email: $authUser->email,
                password: $authUser->password,
                extra: $this->getExtraUserData($authUser)
            );
        }

        throw new InvalidCredentialsException();
    }

    public function update(int $id, array $data): User
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            throw new UserNotFoundException("Usuario con ID $id no encontrado");
        }

        $updateData = [];

        if (isset($data['name'])) {
            $updateData['name'] = $data['name'];
        }

        if (isset($data['email'])) {
            $updateData['email'] = $data['email'];
        }

        if (isset($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if (!empty($updateData)) {
            $updateData['updated_at'] = now();

            try {
                DB::beginTransaction();

                DB::table('users')->where('id', $id)->update($updateData);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw new DomainException('Error al actualizar el usuario: ' . $e->getMessage());
            }
        }

        // Obtenemos el usuario actualizado
        $updatedUser = DB::table('users')->where('id', $id)->first();
        $extraData = $this->getExtraUserData($updatedUser);

        return new User(
            id: $updatedUser->id,
            name: $updatedUser->name,
            email: $updatedUser->email,
            password: $updatedUser->password,
            extra: $extraData
        );
    }

    public function delete(int $id, bool $softDelete = true): bool
    {
        try {
            DB::beginTransaction();

            if ($softDelete) {
                // Soft delete (requiere que la tabla tenga el campo deleted_at)
                $affected = DB::table('users')->where('id', $id)->update([
                    'deleted_at' => now()
                ]);
            } else {
                // Hard delete
                $affected = DB::table('users')->where('id', $id)->delete();
            }

            DB::commit();

            if ($affected === 0) {
                throw new UserNotFoundException("Usuario con ID $id no encontrado");
            }

            return true;
        } catch (UserNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException('Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene los datos adicionales del usuario
     */
    private function getExtraUserData($user): array
    {
        $extraData = [];
        $standardFields = ['id', 'name', 'email', 'password', 'created_at', 'updated_at', 'deleted_at'];

        foreach ((array)$user as $key => $value) {
            if (!in_array($key, $standardFields)) {
                $extraData[$key] = $value;
            }
        }

        return $extraData;
    }
}
