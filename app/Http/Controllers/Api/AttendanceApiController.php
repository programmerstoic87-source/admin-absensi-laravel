<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttendanceApiController extends Controller
{
    public function today(Request $request)
    {
        $user = $request->user();

        $items = Attendance::where('user_id', $user->id)
            ->latest('created_at')
            ->get();

        return response()->json($items);
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'foto_url' => 'nullable|string',
        ]);

        $attendance = Attendance::create([
            'id' => (string) Str::uuid(),
            'branch_id' => $request->branch_id,
            'user_id' => $request->user()->id,
            'type' => 'masuk',
            'status' => 'hadir',
            'timestamp_server' => now(),
            'lat' => $request->lat,
            'lng' => $request->lng,
            'foto_url' => $request->foto_url,
            'keterangan' => 'checkin',
        ]);

        return response()->json(['message' => 'Check-in berhasil', 'data' => $attendance], 201);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'foto_url' => 'nullable|string',
        ]);

        $attendance = Attendance::create([
            'id' => (string) Str::uuid(),
            'branch_id' => $request->branch_id,
            'user_id' => $request->user()->id,
            'type' => 'pulang',
            'status' => 'pulang',
            'timestamp_server' => now(),
            'lat' => $request->lat,
            'lng' => $request->lng,
            'foto_url' => $request->foto_url,
            'keterangan' => 'checkout',
        ]);

        return response()->json(['message' => 'Check-out berhasil', 'data' => $attendance], 201);
    }
}
