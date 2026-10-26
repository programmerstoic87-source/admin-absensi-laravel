<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_cabang',
        'alamat',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'branch_id');
    }
}
