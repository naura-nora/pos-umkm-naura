<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity; // Tambahkan ini
use Spatie\Activitylog\LogOptions;

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



    protected static function booted()
{
    // Ketika transaksi dibuat atau diupdate
    static::saved(function ($transaksi) {
        if ($transaksi->status === 'Lunas') { // Ubah dari 'completed' ke 'Lunas'
            \App\Models\FinancialReport::updateOrCreate(
                [
                    'transaksi_id' => $transaksi->id
                ],
                [
                    'report_date' => $transaksi->tanggal,
                    'description' => 'Transaksi #' . $transaksi->kode,
                    'type' => 'income',
                    'amount' => $transaksi->total,
                    'user_id' => $transaksi->user_id,
                    'source' => 'transaksi'
                ]
            );
        } else {
            // Jika status bukan Lunas, hapus laporan terkait
            \App\Models\FinancialReport::where('transaksi_id', $transaksi->id)->delete();
        }
    });

    // Ketika transaksi dihapus
    static::deleted(function ($transaksi) {
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
        return $this->hasMany(DetailTransaksi::class);
    }

    // Format tanggal: "12/05/2023 14:30"
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal->format('d/m/Y H:i');
    }

    
}