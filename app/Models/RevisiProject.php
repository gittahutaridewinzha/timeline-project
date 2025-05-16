<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiProject extends Model
{
    use HasFactory;

    protected $table = 'revisi_projects';

    protected $fillable = [ 'detailfitur_id', 'note', 'gambar', 'project_job_type_id' ];

    public function detailfitur(){
        return $this->belongsTo(Fitur::class, 'detailfitur_id' );
    }

    Public function jobtype(){
        return $this->belongsTo( ProjectJobTypes::class,'project_job_type_id');
    }
}
