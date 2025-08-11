<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Tambahkan ini
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        // Hapus 'role' karena akan menggunakan sistem role Spatie
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // HAPUS method hasRole() karena sudah disediakan oleh Spatie

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['remember_token', 'updated_at'])
            ->setDescriptionForEvent(function(string $eventName) {
                return "User {$this->name} telah {$eventName}";
            });
    }

    public function getRoleNameAttribute()
{
    return $this->getRoleNames()->first() ?? 'Pelanggan';
}

}