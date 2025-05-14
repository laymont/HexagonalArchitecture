# Arquitectura Hexagonal para Laravel 12

Este paquete proporciona una estructura base para implementar la arquitectura hexagonal (también conocida como Puertos y Adaptadores) en proyectos Laravel 12, facilitando la separación de preocupaciones y el cumplimiento de principios SOLID.

## Características

- Estructura base para arquitectura hexagonal en Laravel
- Migración automática de modelos existentes a la arquitectura hexagonal
- Integración transparente con el ecosistema Laravel
- Soporte para proyectos nuevos y existentes
- Tipado fuerte y validaciones para todos los componentes

## Instalación

```bash
composer require laymont/hexagonal-architecture
```

Luego, publica los archivos de configuración:

```bash
php artisan vendor:publish --provider="Laymont\HexagonalArchitecture\Providers\HexagonalServiceProvider" --tag="config"
```

## Configuración

El archivo `config/hexagonal.php` contiene la configuración del paquete:

```php
return [
    // Configuración de rutas para las capas de arquitectura hexagonal
    'paths' => [
        'domain' => [
            'entities' => 'src/Domain/Entities',
            'value_objects' => 'src/Domain/ValueObjects',
            'exceptions' => 'src/Domain/Exceptions',
        ],
        'application' => [
            'ports' => 'src/Application/Ports',
            'use_cases' => 'src/Application/UseCases',
        ],
        'infrastructure' => [
            'adapters' => 'src/Infrastructure/Adapters',
            'controllers' => 'src/Infrastructure/Controllers',
        ],
    ],
    
    // Configuración para migración de modelos
    'models' => [
        'user' => [
            'model_class' => '\App\Models\User',
            'domain_fields' => ['id', 'name', 'email'],
        ],
        // Añade aquí otros modelos que desees migrar...
    ],
    
    // Si se deben crear providers automáticamente
    'create_providers' => true,
];
```

## Uso

### Migración de modelos a arquitectura hexagonal

Para migrar un modelo existente a la arquitectura hexagonal:

1. Configura el modelo en `config/hexagonal.php`:

```php
'models' => [
    'user' => [
        'model_class' => '\App\Models\User',
        'domain_fields' => ['id', 'name', 'email', 'password'],
    ],
    'product' => [
        'model_class' => '\App\Models\Product',
        'domain_fields' => ['id', 'name', 'price', 'description'],
    ],
],
```

2. Ejecuta el comando de migración:

```bash
php artisan hexagonal:migrate User
```

O para otro modelo:

```bash
php artisan hexagonal:migrate Product
```

El comando generará automáticamente:

- Entidades de dominio
- Objetos de valor
- Excepciones específicas
- Interfaces de puertos (entrada/salida)
- Casos de uso
- Adaptadores de repositorio
- Controladores
- Proveedores de servicios

### Uso de los componentes generados

Una vez migrado un modelo, puedes utilizar los casos de uso en tus controladores:

```php
use Src\Application\Ports\Inbound\UserServiceInterface;

class ApiUserController extends Controller
{
    public function store(Request $request, UserServiceInterface $userService)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
        
        try {
            $user = $userService->create($data);
            return response()->json($user->toArray(), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
```

### Extendiendo la funcionalidad

Puedes extender la funcionalidad creando nuevos casos de uso que implementen la interfaz del servicio.

## Estructura generada

```
src/
├── Domain/
│   ├── Entities/
│   │   └── User.php
│   ├── ValueObjects/
│   │   └── AdditionalUserInfo.php
│   └── Exceptions/
│       ├── DomainException.php
│       └── UserNotFoundException.php
├── Application/
│   ├── Ports/
│   │   ├── Inbound/
│   │   │   └── UserServiceInterface.php
│   │   └── Outbound/
│   │       └── UserRepositoryInterface.php
│   └── UseCases/
│       ├── CreateUser.php
│       ├── UpdateUser.php
│       └── DeleteUser.php
└── Infrastructure/
    ├── Adapters/
    │   └── Database/
    │       └── UserRepository.php
    └── Controllers/
        └── UserController.php
```

## Contribución

Las contribuciones son bienvenidas y serán totalmente acreditadas.

## Licencia

Este paquete es software de código abierto licenciado bajo la [Licencia MIT](LICENSE).
