<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuraciones para migración de modelos a arquitectura hexagonal
    |--------------------------------------------------------------------------
    |
    | Estas configuraciones controlan cómo se comporta el paquete al migrar
    | modelos existentes a la arquitectura hexagonal.
    |
    */
    
    'models' => [
        // Configuración para el modelo User
        'user' => [
            'eloquent_model' => 'App\Models\User',
            'table' => 'users',
            'domain_fields' => ['id', 'name', 'email', 'password'], // Campos básicos del dominio
            'exclude_from_extra' => ['created_at', 'updated_at', 'deleted_at', 'remember_token'],
            'relationships' => [
                'hasMany' => [
                    'posts' => [
                        'model' => 'Post',
                        'foreign_key' => 'user_id',
                        'local_key' => 'id',
                    ],
                    'comments' => [
                        'model' => 'Comment',
                        'foreign_key' => 'user_id',
                        'local_key' => 'id',
                    ],
                ],
                'belongsTo' => [
                    'role' => [
                        'model' => 'Role',
                        'foreign_key' => 'role_id',
                        'owner_key' => 'id',
                    ],
                ],
                'belongsToMany' => [
                    'permissions' => [
                        'model' => 'Permission',
                        'table' => 'user_permissions',
                        'foreign_pivot_key' => 'user_id',
                        'related_pivot_key' => 'permission_id',
                    ],
                ],
                'hasOne' => [
                    'profile' => [
                        'model' => 'Profile',
                        'foreign_key' => 'user_id',
                        'local_key' => 'id',
                    ],
                ],
            ],
        ],
        // Ejemplo de configuración para el modelo Post
        'post' => [
            'eloquent_model' => 'App\Models\Post',
            'table' => 'posts',
            'domain_fields' => ['id', 'title', 'content', 'user_id'],
            'exclude_from_extra' => ['created_at', 'updated_at', 'deleted_at'],
            'relationships' => [
                'belongsTo' => [
                    'user' => [
                        'model' => 'User',
                        'foreign_key' => 'user_id',
                        'owner_key' => 'id',
                    ],
                ],
                'hasMany' => [
                    'comments' => [
                        'model' => 'Comment',
                        'foreign_key' => 'post_id',
                        'local_key' => 'id',
                    ],
                ],
                'morphToMany' => [
                    'tags' => [
                        'model' => 'Tag',
                        'table' => 'taggables',
                        'foreign_pivot_key' => 'taggable_id',
                        'related_pivot_key' => 'tag_id',
                        'relation_name' => 'taggable',
                        'relation_type_column' => 'taggable_type',
                    ],
                ],
            ],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Rutas para generación de código
    |--------------------------------------------------------------------------
    |
    | Estas configuraciones definen dónde se crean los nuevos archivos cuando
    | se migra un modelo existente a arquitectura hexagonal.
    |
    */
    
    'paths' => [
        'domain' => [
            'entities' => 'app/Domain/Entities',
            'value_objects' => 'app/Domain/ValueObjects',
            'exceptions' => 'app/Domain/Exceptions',
        ],
        'application' => [
            'ports' => 'app/Application/Ports',
            'use_cases' => 'app/Application/UseCases',
        ],
        'infrastructure' => [
            'adapters' => 'app/Infrastructure/Adapters/Database',
            'controllers' => 'app/Infrastructure/Http/Controllers',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración del CLI interactivo
    |--------------------------------------------------------------------------
    |
    | Estas configuraciones controlan el comportamiento de la interfaz de línea
    | de comandos interactiva para la migración de modelos.
    |
    */

    'cli' => [
        'interactive' => true,  // Activar modo interactivo por defecto
        'confirm_overwrite' => true,  // Confirmar antes de sobrescribir archivos
        'auto_discover_models' => true, // Buscar modelos Eloquent automáticamente
    ],
];
