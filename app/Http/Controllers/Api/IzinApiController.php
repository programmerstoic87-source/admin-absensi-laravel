<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IzinApiController extends Controller
{
    public function request(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'tanggal' => 'required|date',
            'alasan' => 'required|string',
            'foto_url' => 'nullable|string',
        ]);

        $izin = Izin::create([
            'id' => (string) Str::uuid(),
            'branch_id' => $request->branch_id,
            'user_id' => $request->user()->id,
            'tanggal' => $request->tanggal,
            'alasan' => $request->alasan,
            'foto_url' => $request->foto_url,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Izin berhasil diajukan', 'data' => $izin], 201);
    }

    public function history(Request $request)
    {
        $items = Izin::where('user_id', $request->user()->id)->latest('created_at')->get();

        return response()->json($items);
    }
}
