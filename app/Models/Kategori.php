<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity; // Tambahkan ini
use Spatie\Activitylog\LogOptions;

class Kategori extends Model
{
    use HasFactory;

    use LogsActivity;

    protected $table = 'kategori';
    
    protected $fillable = [
        'nama_kategori',
    ];
    
    /**
     * Relasi one-to-many ke model Produk
     */
    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_kategori'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function(string $eventName) {
                return "Kategori {$this->nama_kategori} telah {$eventName}";
            });
    }

}