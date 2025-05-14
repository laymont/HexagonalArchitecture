<?php
namespace Laymont\HexagonalArchitecture\Providers;

use Illuminate\Support\ServiceProvider;
use Laymont\HexagonalArchitecture\Application\Ports\Inbound\UserServiceInterface;
use Laymont\HexagonalArchitecture\Application\Ports\Outbound\UserRepositoryInterface;
use Laymont\HexagonalArchitecture\Application\UseCases\DeleteUser;
use Laymont\HexagonalArchitecture\Application\UseCases\LoginUser;
use Laymont\HexagonalArchitecture\Application\UseCases\RegisterUser;
use Laymont\HexagonalArchitecture\Application\UseCases\UpdateUserProfile;
use Laymont\HexagonalArchitecture\Console\Commands\MigrateModelToHexagonal;
use Laymont\HexagonalArchitecture\Infrastructure\Adapters\Database\UserRepository;

class HexagonalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Binding de repositorios
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        
        // Binding de casos de uso a sus interfaces
        $this->app->bind('hex.user.register', fn($app) => new RegisterUser(
            $app->make(UserRepositoryInterface::class)
        ));
        
        $this->app->bind('hex.user.login', fn($app) => new LoginUser(
            $app->make(UserRepositoryInterface::class)
        ));
        
        $this->app->bind('hex.user.update', fn($app) => new UpdateUserProfile(
            $app->make(UserRepositoryInterface::class)
        ));
        
        $this->app->bind('hex.user.delete', fn($app) => new DeleteUser(
            $app->make(UserRepositoryInterface::class)
        ));
    }

    public function boot(): void
    {
        // Publicar configuraciones
        $this->publishes([
            __DIR__ . '/../../config/hexagonal.php' => config_path('hexagonal.php'),
        ], 'hexagonal-config');
        
        // Cargar el archivo de configuración
        $this->mergeConfigFrom(__DIR__ . '/../../config/hexagonal.php', 'hexagonal');
        
        // Registrar comandos si estamos en consola
        if ($this->app->runningInConsole()) {
            $this->commands([
                MigrateModelToHexagonal::class,
            ]);
        }
    }
}
