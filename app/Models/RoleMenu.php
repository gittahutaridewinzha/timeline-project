<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    use HasFactory;

    protected $table = 'role_menus';

    protected $fillable = ['role_id', 'menu_id'];

    public function role()
    {
        return $this->belongsTo(Roles::class);
    }

    /**
     * Relasi ke model Menu
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
