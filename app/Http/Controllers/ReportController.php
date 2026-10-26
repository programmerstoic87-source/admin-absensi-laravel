<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Izin;

class ReportController extends Controller
{
    public function index()
    {
        $totalHadir = Attendance::where('status', 'hadir')->count();
        $totalTerlambat = Attendance::where('status', 'terlambat')->count();
        $totalIzin = Izin::where('status', 'approved')->count();

        return view('reports.index', compact('totalHadir', 'totalTerlambat', 'totalIzin'));
    }
}
