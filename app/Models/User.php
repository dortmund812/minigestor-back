<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    // Roles sugeridos
    public const ROLE_ADMIN  = 'admin';
    public const ROLE_MEMBER = 'user';

    protected $table = 'users';

    protected $fillable = [
        'uuid',
        'name',
        'last_name',
        'email',
        'password',
        'status',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status'            => 'boolean',
        'role'              => 'string',
    ];

    public function tasksCreated()
    {
        return $this->hasMany(Task::class, 'user_creator');
    }

    public function tasksAssigned()
    {
        return $this->hasMany(Task::class, 'user_assigned');
    }
}
