<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskDistribution extends Model
{
    use HasFactory;

    protected $table = 'task_distributions';

    protected $fillable = [
        'project_id',
        'job_types_id',
        'user_id',
    ];

    // Relasi ke Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relasi ke JobType (asumsi nama model-nya JobType)
    public function jobType()
    {
        return $this->belongsTo(JobType::class, 'job_types_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function projectJobType()
    {
        return $this->belongsTo(ProjectJobTypes::class, 'project_job_type_id');
    }

}
