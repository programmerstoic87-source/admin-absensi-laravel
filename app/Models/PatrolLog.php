<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrolLog extends Model
{
    use HasFactory;

    protected $table = 'patrol_logs';

    protected $fillable = [
        'branch_id',
        'user_id',
        'checkpoint_id',
        'timestamp_server',
        'lat',
        'lng',
        'foto_url',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function checkpoint()
    {
        return $this->belongsTo(PatrolCheckpoint::class, 'checkpoint_id');
    }
}
