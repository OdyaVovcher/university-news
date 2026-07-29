<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;                       

#[Fillable(['name', 'email', 'password', 'is_admin', 'group_id','faculty','specialty',
    'group'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Связь с моделью Group (One-to-Many Inverse)
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Быстрый доступ к факультету через цепочку связей: Group -> Specialty -> Faculty
     */
    public function getFacultyAttribute()
    {
        // Проверяем существование связи, чтобы не уходить в рекурсию при жадной загрузке
        if ($this->relationLoaded('group') && $this->group) {
            return $this->group->specialty?->faculty;
        }

        return $this->group?->specialty?->faculty;
    }
}