<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrolCheckpoint extends Model
{
    use HasFactory;

    protected $table = 'patrol_checkpoints';

    protected $fillable = [
        'branch_id',
        'location_id',
        'nama',
        'lat',
        'lng',
        'radius_meter',
        'urutan',
        'is_active',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
