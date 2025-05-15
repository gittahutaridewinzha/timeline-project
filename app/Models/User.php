<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'role_id', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Roles::class);
    }

    // User.php
    public function taskDistributions()
    {
        return $this->hasMany(TaskDistribution::class, 'user_id');
    }

    public function hasAccessToMenu($route)
    {
        // Ambil semua nama route yang diizinkan dari database
        $allowedRoutes = $this->role->menus()->pluck('route')->toArray();

        // Cek apakah nama route yang diakses mengandung salah satu yang ada di database
        foreach ($allowedRoutes as $allowedRoute) {
            if (str_starts_with($route, $allowedRoute)) {
                return true;
            }
        }

        return false;
    }
}
