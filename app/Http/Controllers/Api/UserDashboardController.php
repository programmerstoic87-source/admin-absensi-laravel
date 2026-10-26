<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Izin;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function summary(Request $request)
    {
        $user = $request->user();

        $hadir = Attendance::where('user_id', $user->id)->where('status', 'hadir')->count();
        $terlambat = Attendance::where('user_id', $user->id)->where('status', 'terlambat')->count();
        $izinPending = Izin::where('user_id', $user->id)->where('status', 'pending')->count();

        return response()->json([
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'izin_pending' => $izinPending,
        ]);
    }
}
