<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Izin;
use App\Models\PatrolCheckpoint;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $branchFilter = ($user->jabatan === 'Admin' || $user->jabatan === 'Chief') ? null : $user->branch_id;

        $totalCabang = Branch::query()->when($branchFilter, fn($q) => $q->where('id', $branchFilter))->count();
        $totalUser = User::query()->when($branchFilter, fn($q) => $q->where('branch_id', $branchFilter))->count();
        $totalHadir = Attendance::query()->when($branchFilter, fn($q) => $q->where('branch_id', $branchFilter))->where('status', 'hadir')->count();
        $totalTerlambat = Attendance::query()->when($branchFilter, fn($q) => $q->where('branch_id', $branchFilter))->where('status', 'terlambat')->count();
        $izinPending = Izin::query()->when($branchFilter, fn($q) => $q->where('branch_id', $branchFilter))->where('status', 'pending')->count();
        $totalPatroli = PatrolCheckpoint::query()->when($branchFilter, fn($q) => $q->where('branch_id', $branchFilter))->count();

        $recentAttendance = Attendance::with('user')
            ->when($branchFilter, fn($q) => $q->where('branch_id', $branchFilter))
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'totalCabang',
            'totalUser',
            'totalHadir',
            'totalTerlambat',
            'izinPending',
            'totalPatroli',
            'recentAttendance'
        ));
    }
}
