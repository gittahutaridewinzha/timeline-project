<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengerjaan extends Model
{
    use HasFactory;

    protected $table = 'pengerjaans';

    protected $fillable = ['user_id', 'detail_fiturs_id', 'pengerjaan','project_job_type_id'];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model DetailFitur
     */
    public function detailFiturs()
    {
        return $this->belongsTo(DetailFitur::class, 'detail_fiturs_id');
    }
    public function fitur()
    {
        return $this->belongsTo(Fitur::class, 'fitur_id');
    }

    // Pada model Pengerjaan
    public function taskDistribution()
    {
        return $this->hasOne(TaskDistribution::class, 'project_id', 'project_id');
    }
    // Pada model Pengerjaan
    public function project()
    {
        return $this->belongsTo(Project::class); // pastikan relasi ini benar
    }
    public function taskDistributions()
    {
        return $this->hasMany(TaskDistribution::class);
    }
    public function projectJobType()
    {
        return $this->belongsTo(ProjectJobTypes::class);
    }



}
