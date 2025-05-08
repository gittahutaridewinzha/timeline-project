<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'project';

    protected $fillable = [
        'nama_project',
        'deskripsi',
        'id_project_manager',
        'category_id',
    ];

    public function ProjectManager()
    {
        return $this->belongsTo(User::class, 'id_project_manager');
    }

    public function CategoryProject()
    {
        return $this->belongsTo(CategoryProject::class);
    }

    public function jobTypes()
    {
        return $this->belongsToMany(JobType::class, 'project_job_types', 'project_id', 'job_id');
    }
}
