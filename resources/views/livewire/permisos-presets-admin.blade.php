<div class="container py-3">
    <div class="d-flex flex-column flex-lg-row gap-3 justify-content-between align-items-lg-center mb-3">
        <div>
            <h3 class="mb-1">Presets de roles</h3>
            <p class="text-muted mb-0">Definí la plantilla de permisos que se puede aplicar a cada rol.</p>
        </div>

        <div class="d-flex flex-column flex-sm-row gap-2">
            <a href="{{ route('admin.usuarios') }}" class="btn btn-outline-secondary text-nowrap">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>

            <a href="{{ route('admin.permisos') }}" class="btn btn-outline-primary text-nowrap">
                <i class="bi bi-shield-check me-1"></i> Permisos por escuela
            </a>

            <select wire:model.live="roleName" class="form-select">
                @foreach ($roles as $role)
                    <option value="{{ $role }}">{{ $roleLabels[$role] ?? ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex flex-column flex-md-row gap-2 justify-content-between align-items-md-center">
            <div>
                <h5 class="mb-1">{{ $roleLabels[$roleName] ?? ucfirst($roleName) }}</h5>
                <p class="text-muted small mb-0">
                    {{ $presetPersonalizado ? 'Preset personalizado' : 'Preset predeterminado del sistema' }}
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button type="button" wire:click="cargarPredeterminado" class="btn btn-outline-secondary btn-sm">
                    Restaurar predeterminado
                </button>
                <button type="button" wire:click="guardar" class="btn btn-primary btn-sm">
                    Guardar preset
                </button>
                <button type="button"
                    wire:click="eliminar"
                    class="btn btn-outline-danger btn-sm"
                    @disabled(! $presetPersonalizado)>
                    Eliminar personalizado
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 220px;">Módulo</th>
                            <th style="min-width: 260px;">Permiso</th>
                            <th class="text-center" style="width: 12%;">Incluido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($modules as $moduleKey => $module)
                            @foreach ($module['permissions'] as $actionKey => $label)
                                <tr wire:key="preset-{{ $moduleKey }}-{{ $actionKey }}">
                                    @if ($loop->first)
                                        <td class="fw-semibold" rowspan="{{ count($module['permissions']) }}">
                                            {{ $module['label'] }}
                                        </td>
                                    @endif
                                    <td>
                                        <span class="fw-semibold" title="{{ $moduleKey }}.{{ $actionKey }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox"
                                            wire:model.defer="checked.{{ $moduleKey }}.{{ $actionKey }}"
                                            class="form-check-input"
                                            aria-label="{{ $module['label'] }} - {{ $label }}">
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No hay permisos definidos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
