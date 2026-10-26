<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index()
    {
        $izin = Izin::with('user')->latest('created_at')->get();

        return view('izin.index', compact('izin'));
    }

    public function approve($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->status = 'approved';
        $izin->reviewed_by = auth()->id();
        $izin->reviewed_at = now();
        $izin->save();

        return redirect()->route('izin.index')->with('success', 'Izin disetujui');
    }

    public function reject($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->status = 'rejected';
        $izin->reviewed_by = auth()->id();
        $izin->reviewed_at = now();
        $izin->save();

        return redirect()->route('izin.index')->with('success', 'Izin ditolak');
    }
}
