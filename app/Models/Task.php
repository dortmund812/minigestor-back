<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory;

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public const TASK_STATUS_PENDING     = 'pending';
    public const TASK_STATUS_IN_PROGRESS = 'in_progress';
    public const TASK_STATUS_DONE        = 'done';

    protected $table = 'tasks';

    protected $fillable = [
        'uuid',
        'title',
        'description',
        'status',       // boolean (activa/inactiva)
        'task_status',  // enum (pending, in_progress, done)
        'user_creator',
        'user_assigned',
    ];

    protected $casts = [
        'status'      => 'boolean',
        'task_status' => 'string',
    ];

    /**
     * Usuario que creó la tarea.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_creator');
    }

    /**
     * Usuario asignado a la tarea.
     */
    public function assigned()
    {
        return $this->belongsTo(User::class, 'user_assigned');
    }
}
