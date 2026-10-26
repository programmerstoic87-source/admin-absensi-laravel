<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::with(['user', 'branch', 'location'])
            ->latest('created_at')
            ->get();

        return view('attendance.index', compact('attendance'));
    }

    public function manualCorrection(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendance,id',
            'status' => 'required|in:hadir,terlambat,izin,tanpa_keterangan,pulang',
            'keterangan' => 'nullable|string',
        ]);

        $attendance = Attendance::findOrFail($request->attendance_id);
        $attendance->status = $request->status;
        $attendance->keterangan = $request->keterangan ?? 'Koreksi admin';
        $attendance->save();

        return redirect()->route('attendance.index')->with('success', 'Absensi berhasil dikoreksi manual');
    }
}
