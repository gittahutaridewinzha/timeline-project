<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fitur extends Model
{
    use HasFactory;

    protected $table = 'fiturs';

    protected $fillable = ['project_id', 'name'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function detailFiturs()
    {
        return $this->hasMany(DetailFitur::class);
    }
}
