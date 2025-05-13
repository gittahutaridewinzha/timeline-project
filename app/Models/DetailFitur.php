<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailFitur extends Model
{
    use HasFactory;

    protected $table = 'detail_fiturs';

    protected $fillable = ['fitur_id', 'name'];

    // App\Models\DetailFitur.php
public function fitur()
{
    return $this->belongsTo(Fitur::class, 'fitur_id');
}


    public function detailFiturs()
    {
        return $this->hasMany(DetailFitur::class);
    }
}
