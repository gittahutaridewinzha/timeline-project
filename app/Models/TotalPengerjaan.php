<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TotalPengerjaan extends Model
{
    use HasFactory;

    protected $table = 'total_pengerjaans';

    protected $fillable = [
        'detail_fiturs_id',
        'total_pengerjaan',
    ];

    /**
     * Relasi ke model DetailFitur
     */
    public function detailFitur()
    {
        return $this->belongsTo(DetailFitur::class, 'detail_fiturs_id');
    }
}
