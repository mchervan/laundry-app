<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'branch'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    public function hasBranchAccess($branchName): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        return $this->branch === $branchName;
    }

    public static function getAvailableBranches(): array
    {
        return ['Pemuda', 'Serayu', 'Setia Budi', 'Munggut'];
    }

    public function scopeByCabang($query, $cabang)
    {
        if ($cabang && $cabang !== 'all') {
            return $query->where('branch', $cabang);
        }
        return $query;
    }
}