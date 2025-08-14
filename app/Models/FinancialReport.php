<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{

    protected $casts = [
        'report_date' => 'datetime',
    ];

     protected $fillable = [
        'report_date', 
        'description',
        'type',
        'amount',
        'user_id',
        'transaksi_id',
        'source',
        'responsible'
    ];

    protected $dates = ['report_date']; // Untuk otomatis parse ke Carbon

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
