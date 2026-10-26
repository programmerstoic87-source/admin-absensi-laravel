<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    protected $table = 'izin';

    protected $fillable = [
        'branch_id',
        'user_id',
        'tanggal',
        'alasan',
        'foto_url',
        'status',
        'catatan',
        'reviewed_by',
        'reviewed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
