# Horarios Escolares

Sistema de gestión de horarios para instituciones educativas, desarrollado con Laravel y Livewire.

## Descripción

Esta aplicación permite administrar cursos, materias, docentes, espacios físicos, horarios y cambios de horario para una institución educativa. Incluye dashboards de administración, selección de institución activa, permisos basados en roles y generación de PDF para horarios y actas.

## Características principales

- Autenticación de usuarios con Laravel Breeze.
- Selección de institución activa por usuario.
- Administración de:
  - Instituciones
  - Materias
  - Usuarios
  - Cursos y materias de curso
  - Docentes
  - Espacios físicos y su utilización
  - Horarios base y cambios de horario
- Alertas de superposición de docentes.
- Paneles Livewire para administración y visualización de datos.
- Generación de PDF para:
  - Horario de curso
  - Utilización de espacios
  - Acta de cambio de horario
- Control de acceso mediante permisos (`spatie/laravel-permission`).

## Tecnologías

- PHP 8.2
- Laravel 12
- Livewire 4
- Tailwind CSS
- Vite
- Alpine.js
- DOMPDF
- Spatie Permission

## Estructura del proyecto

- `app/Livewire/`: componentes Livewire para gestión de horarios, cursos, espacios y paneles.
- `app/Http/Controllers/`: controladores para selección de institución, PDF, perfil y autenticación.
- `app/Models/`: modelos de dominio como `Curso`, `Docente`, `EspacioFisico`, `HorarioBase`, `CambioHorario`, `Institucion` y más.
- `resources/views/`: vistas de administración, dashboard, auth, perfil y PDF.
- `routes/web.php`: rutas principales con middleware de autenticación y permisos.

## Requisitos

- PHP >= 8.2
- Composer
- Node.js (recomendado 18+)
- npm
- Base de datos compatible con Laravel (MySQL / SQLite / PostgreSQL)

## Instalación

```bash
cd /sitios/horarios-escolares
composer install
cp .env.example .env
php artisan key:generate
```

Configura tu conexión a base de datos en `.env` y luego ejecuta:

```bash
php artisan migrate
npm install
npm run build
```

## Desarrollo

Ejecuta el servidor y el compilador de assets en modo desarrollo:

```bash
npm run dev
```

También puedes usar el script definido en Composer:

```bash
composer run-script dev
```

## Pruebas

```bash
composer run-script test
```

## Rutas importantes

- `/instituciones/seleccionar` - Selección de institución activa.
- `/dashboard` - Panel principal.
- `/admin/instituciones`, `/admin/materias`, `/admin/usuarios` - Administración global.
- `/admin/cursos/listado`, `/admin/cursos/materias` - Gestión de cursos.
- `/admin/docentes` - Gestión de docentes.
- `/admin/espacios/utilizacion`, `/admin/espacios/administracion` - Gestión de espacios físicos.
- `/admin/cambios-horario` - Gestión de cambios de horario.
- `/admin/alertas/superposiciones-docentes` - Detección de conflictos de horario entre docentes.
- `/profile` - Edición de perfil.

## Notas

- El acceso a secciones administrativas se controla mediante permisos como `abm-cursos`, `abm-docentes`, `abm-espacios` y `ver-cambios-horario`.
- La generación de PDF utiliza `barryvdh/laravel-dompdf`.

## Licencia

Proyecto con licencia MIT.
