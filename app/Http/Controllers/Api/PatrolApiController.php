<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PatrolCheckpoint;
use App\Models\PatrolLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatrolApiController extends Controller
{
    public function index()
    {
        return response()->json(PatrolCheckpoint::all());
    }

    public function scan(Request $request)
    {
        $request->validate([
            'checkpoint_id' => 'required|exists:patrol_checkpoints,id',
            'branch_id' => 'required|exists:branches,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'foto_url' => 'nullable|string',
        ]);

        $log = PatrolLog::create([
            'id' => (string) Str::uuid(),
            'branch_id' => $request->branch_id,
            'user_id' => $request->user()->id,
            'checkpoint_id' => $request->checkpoint_id,
            'timestamp_server' => now(),
            'lat' => $request->lat,
            'lng' => $request->lng,
            'foto_url' => $request->foto_url,
        ]);

        return response()->json(['message' => 'Patroli berhasil dicatat', 'data' => $log], 201);
    }
}
