<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValueProject extends Model
{
    use HasFactory;

    protected $table = 'value_projects';

    protected $fillable = ['project_id', 'value_project', 'amount', 'payment_category'];

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
