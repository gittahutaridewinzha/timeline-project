<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_job_types', 'project_id', 'job_id');
    }

    public function projectsWithUsers()
    {
        return $this->belongsToMany(Project::class, 'task_distributions', 'job_types_id', 'project_id')
            ->withPivot('user_id');
    }

}
