<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectJobTypes extends Model
{
    use HasFactory;

    protected $table = 'project_job_types';

    protected $fillable = ['project_id', 'job_id', 'category_id'];

    public function jobtype()
    {
        return $this->belongsTo(JobType::class, 'job_id'); // pastikan foreign key-nya benar
    }

    public function taskDistributions()
    {
        // Asumsi TaskDistribution punya foreign key project_job_type_id
        return $this->hasMany(TaskDistribution::class, 'project_job_type_id');
    }
}
