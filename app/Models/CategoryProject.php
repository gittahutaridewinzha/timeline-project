<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProject extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    // Di dalam Project.php, kamu punya ini:
    // Model CategoryProject
    public function jobTypes()
    {
        return $this->belongsToMany(JobType::class, 'category_projects_detail', 'id_category_projects', 'id_job_types');
    }

}
