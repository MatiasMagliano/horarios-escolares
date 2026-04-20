---
description: Laravel 12 & Livewire 4 School Schedule Project Architecture
---

## Project Structure
- Models: `app/Models`
- Controllers: `app/Http/Controllers`
- Blade Views: `resources/views`
- Routes: `routes/web.php` and `routes/api.php`

## Livewire Component Conventions
- **Hybrid Structure**: We use both traditional and Volt-style (View-first) components.
- **Traditional Components**: Logic resides in `app/Livewire/*.php` and templates in `resources/views/livewire/*.blade.php`. Use this for complex business logic.
- **View-first Components**: Use single-file components (Volt/Anonymous) for simple UI elements or rapid prototyping.
- **Discovery**: When asked to modify a component, check both `app/Livewire` and `resources/views/livewire` to ensure you see the full context.

## Tech Stack Requirements
- Framework: Laravel 12.x (Use PHP 8.4 features like property hooks where applicable)
- Frontend: Livewire 4.x (Prioritize "Islands" for partial updates)
- UI: Bootstrap 5.3 (Use utility classes, avoid custom CSS)