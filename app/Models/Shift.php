<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'nama_shift',
        'jenis_shift',
        'jam_masuk',
        'jam_pulang',
        'toleransi_menit',
        'is_active',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
