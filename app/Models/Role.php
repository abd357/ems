<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as RoleModel;

class Role extends RoleModel
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    protected $attributes = [
        'guard_name' => 'web'
    ];
}
