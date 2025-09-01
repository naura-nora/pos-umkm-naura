<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity; // Tambahkan ini
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{

    use LogsActivity;

    use HasFactory;

    protected $table = 'transaksi';

    protected $casts = [
        'tanggal' => 'datetime',
        'total' => 'float',
        'bayar' => 'float',
        'kembalian' => 'float',
    ];

    protected $fillable = [
        'user_id',
        'kode',
        'nama_pelanggan',
        'total',
        'bayar',
        'kembalian',
        'tanggal',
        'status'
    ];


    // public function details(): HasMany
    // {
    //     return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    // }

    protected static function boot()
    {
        parent::boot();

        // Event ketika transaksi diupdate
        static::updated(function($transaksi) {
            if ($transaksi->isDirty('status') && $transaksi->status === 'completed') {
                // Cek apakah sudah ada laporan untuk transaksi ini
                $existingReport = \App\Models\FinancialReport::where('transaksi_id', $transaksi->id)->first();
                
                if (!$existingReport) {
                    \App\Models\FinancialReport::create([
                        'report_date' => $transaksi->tanggal,
                        'description' => 'Pendapatan dari transaksi #' . $transaksi->kode,
                        'type' => 'income',
                        'amount' => $transaksi->total,
                        'user_id' => $transaksi->user_id,
                        'transaksi_id' => $transaksi->id,
                        'source' => 'transaksi'
                    ]);
                }
            }

            // Jika status diubah dari completed ke lainnya
            if ($transaksi->isDirty('status') && $transaksi->getOriginal('status') === 'completed') {
                \App\Models\FinancialReport::where('transaksi_id', $transaksi->id)->delete();
            }
        });

        // Event ketika transaksi dihapus
        static::deleted(function($transaksi) {
            \App\Models\FinancialReport::where('transaksi_id', $transaksi->id)->delete();
        });
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama_pelanggan', 'total', 'status'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function(string $eventName) {
                return "Transaksi {$this->kode} telah {$eventName}";
            });
    }


    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    // Format tanggal: "12/05/2023 14:30"
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal->format('d/m/Y H:i');
    }

    
}