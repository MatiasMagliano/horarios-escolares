<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermissionPreset extends Model
{
    protected $fillable = [
        'role_name',
        'permissions',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }
}
