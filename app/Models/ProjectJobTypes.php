<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectJobTypes extends Model
{
    use HasFactory;

    protected $table = 'project_job_types';

    protected $fillable = ['project_id', 'job_id', 'category_id'];
}
