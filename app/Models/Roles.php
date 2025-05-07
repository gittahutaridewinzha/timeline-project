<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['name']; // Field yang bisa diisi

    /**
     * Relasi ke Admin (One to Many).
     * Satu role bisa digunakan oleh banyak admin.
     */
    public function admins()
    {
        return $this->hasMany(User::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_menus', 'role_id', 'menu_id');
    }
}
