<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity; // Tambahkan ini
use Spatie\Activitylog\LogOptions;

class Produk extends Model
{
    use LogsActivity;

    protected $table = 'produk';
    
    protected $fillable = [
        'nama_produk',
        'harga_produk', 
        'stok_produk',
        'kategori_id',
        'gambar_produk'
    ];
    
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_produk', 'harga_produk', 'stok_produk', 'kategori_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return "Produk {$this->nama_produk} telah {$eventName}";
            });
    }


    // Accessor untuk URL gambar lengkap
    public function getGambarUrlAttribute()
    {
        if ($this->gambar_produk) {
            return asset('adminlte/img/'.$this->gambar_produk);
        }
        return null;
    }
}