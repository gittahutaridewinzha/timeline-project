<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    use HasFactory;

    protected $table = 'project_types';

    protected $fillable = ['name'];

    public function projects()
    {
        return $this->hasMany(Project::class, 'id_project_type');
    }

}
