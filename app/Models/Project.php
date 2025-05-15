<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'project';

    protected $fillable = ['nama_project', 'deskripsi', 'id_project_manager', 'category_id','deadline','status'];

    public function ProjectManager()
    {
        return $this->belongsTo(User::class, 'id_project_manager');
    }

    public function CategoryProject()
    {
        return $this->belongsTo(CategoryProject::class);
    }

    // Di CategoryProject.php
    public function jobTypes()
    {
        return $this->belongsToMany(JobType::class, 'project_job_types', 'project_id', 'job_id')->withPivot('category_id')->withTimestamps();
    }

    public function taskDistributions()
    {
        return $this->hasMany(TaskDistribution::class);
    }

    public function jobTypeAssignments()
    {
        return $this->belongsToMany(JobType::class, 'task_distributions', 'project_id', 'job_types_id')->withPivot('user_id')->withTimestamps();
    }

    public function fiturs()
    {
        return $this->hasMany(Fitur::class);
    }

    public function valueProject()
    {
        return $this->hasOne(ValueProject::class);
    }

    public function projectJobTypes()
    {
        return $this->hasMany(ProjectJobTypes::class, 'project_id');
    }
}
