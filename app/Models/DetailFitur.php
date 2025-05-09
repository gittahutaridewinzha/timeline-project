<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailFitur extends Model
{
    use HasFactory;

    protected $table = 'detail_fiturs';

    protected $fillable = ['fitur_id', 'name'];

    public function fitur()
    {
        return $this->belongsToMany(Fitur::class);
    }

    public function detailFiturs()
    {
        return $this->hasMany(DetailFitur::class);
    }
}
