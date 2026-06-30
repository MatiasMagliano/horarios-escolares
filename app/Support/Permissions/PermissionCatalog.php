<?php

namespace App\Support\Permissions;

class PermissionCatalog
{
    public const ROLES = [
        'administrador',
        'preceptor',
        'aprobador',
        'secretario',
    ];

    /**
     * @return array<string, string>
     */
    public static function roleLabels(): array
    {
        return [
            'administrador' => 'Administrador',
            'preceptor' => 'Preceptor',
            'aprobador' => 'Aprobador',
            'secretario' => 'Secretario',
        ];
    }

    /**
     * @return array<string, array{label: string, permissions: array<string, string>}>
     */
    public static function modules(): array
    {
        return [
            'schedules' => [
                'label' => 'Horarios',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Borrar',
                    'export_pdf' => 'Exportar PDF',
                ],
            ],
            'time_blocks' => [
                'label' => 'Bloques horarios',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Borrar',
                ],
            ],
            'courses' => [
                'label' => 'Cursos',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Borrar',
                ],
            ],
            'course_subjects' => [
                'label' => 'Materias por curso',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Borrar',
                ],
            ],
            'teachers' => [
                'label' => 'Docentes',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Borrar',
                    'activate' => 'Activar',
                ],
            ],
            'subjects' => [
                'label' => 'Materias',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Borrar',
                ],
            ],
            'spaces' => [
                'label' => 'Espacios',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Borrar',
                    'utilization' => 'Utilización',
                ],
            ],
            'schedule_changes' => [
                'label' => 'Cambios horarios',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Anular',
                    'approve' => 'Aprobar',
                    'manage' => 'Gestionar',
                    'effective' => 'Efectivizar',
                    'sign' => 'Firmar',
                ],
            ],
            'institutions' => [
                'label' => 'Instituciones',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Borrar',
                ],
            ],
            'users' => [
                'label' => 'Usuarios',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Borrar',
                ],
            ],
            'roles' => [
                'label' => 'Roles',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Borrar',
                ],
            ],
            'permissions' => [
                'label' => 'Permisos',
                'permissions' => [
                    'view' => 'Ver',
                    'create' => 'Crear',
                    'update' => 'Editar',
                    'delete' => 'Borrar',
                ],
            ],
            'dashboard' => [
                'label' => 'Dashboard',
                'permissions' => [
                    'view' => 'Ver',
                ],
            ],
            'reports' => [
                'label' => 'Reportes',
                'permissions' => [
                    'generate' => 'Generar',
                ],
            ],
            'alerts' => [
                'label' => 'Alertas',
                'permissions' => [
                    'superpositions' => 'Superposiciones',
                ],
            ],
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function names(): array
    {
        $names = [];

        foreach (self::modules() as $module => $definition) {
            foreach (array_keys($definition['permissions']) as $action) {
                $names[] = "{$module}.{$action}";
            }
        }

        return $names;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function rolePermissions(): array
    {
        return [
            'administrador' => self::names(),
            'preceptor' => self::onlyActions([
                'schedules' => ['view', 'export_pdf'],
                'time_blocks' => ['view'],
                'courses' => ['view'],
                'course_subjects' => ['view'],
                'teachers' => ['view'],
                'subjects' => ['view'],
                'spaces' => ['view', 'utilization'],
                'schedule_changes' => ['view'],
                'dashboard' => ['view'],
                'reports' => ['generate'],
                'alerts' => ['superpositions'],
            ]),
            'aprobador' => self::onlyActions([
                'schedules' => ['view', 'export_pdf'],
                'time_blocks' => ['view'],
                'courses' => ['view'],
                'course_subjects' => ['view'],
                'teachers' => ['view', 'create', 'update', 'delete', 'activate'],
                'subjects' => ['view'],
                'spaces' => ['view', 'utilization'],
                'schedule_changes' => ['view', 'create', 'update', 'delete', 'approve', 'manage', 'effective'],
                'dashboard' => ['view'],
                'reports' => ['generate'],
                'alerts' => ['superpositions'],
            ]),
            'secretario' => self::onlyActions([
                'schedules' => ['view', 'export_pdf'],
                'time_blocks' => ['view'],
                'courses' => ['view'],
                'course_subjects' => ['view'],
                'teachers' => ['view', 'create', 'update', 'delete'],
                'subjects' => ['view', 'create', 'update', 'delete'],
                'spaces' => ['view', 'utilization'],
                'schedule_changes' => ['view', 'create', 'update', 'delete', 'sign'],
                'dashboard' => ['view'],
                'reports' => ['generate'],
                'alerts' => ['superpositions'],
            ]),
        ];
    }

    /**
     * @param array<string, array<int, string>> $modules
     * @return array<int, string>
     */
    private static function onlyActions(array $modules): array
    {
        $permissions = [];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $permissions[] = "{$module}.{$action}";
            }
        }

        return $permissions;
    }
}
