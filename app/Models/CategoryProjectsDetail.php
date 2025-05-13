<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryProjectsDetail extends Model
{
    // Nama tabel jika tidak mengikuti konvensi Laravel
    protected $table = 'category_projects_detail';

    // Kolom yang boleh diisi
    protected $fillable = [
        'id_category_projects',
        'id_job_types',
    ];

    // Jika tabel tidak memiliki kolom timestamps (created_at, updated_at)
    public $timestamps = false;

    // Relasi ke model CategoryProject
    public function categoryProject()
    {
        return $this->belongsTo(CategoryProject::class, 'id_category_projects');
    }

    // Relasi ke model JobType
    public function jobType()
    {
        return $this->belongsTo(JobType::class, 'id_job_types');
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
