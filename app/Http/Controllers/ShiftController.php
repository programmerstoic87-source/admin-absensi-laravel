<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Branch;
use App\Models\Shifts;

class ShiftController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $shifts = \App\Models\Shift::with('branch')
            ->when($user->jabatan !== 'Admin' && $user->jabatan !== 'Chief', fn($q) => $q->where('branch_id', $user->branch_id))
            ->get();

        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('shifts.create', compact('branches'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'nama_shift' => 'required|string',
            'jenis_shift' => 'required|string',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'toleransi_menit' => 'required|integer',
        ]);

        \App\Models\Shift::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'branch_id' => $request->branch_id,
            'nama_shift' => $request->nama_shift,
            'jenis_shift' => $request->jenis_shift,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'toleransi_menit' => $request->toleransi_menit,
            'is_active' => true,
        ]);

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil ditambah');
    }

    public function edit(\App\Models\Shift $shift)
    {
        $branches = Branch::all();
        return view('shifts.edit', compact('shift', 'branches'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Shift $shift)
    {
        $shift->update($request->all());
        return redirect()->route('shifts.index')->with('success', 'Shift berhasil diupdate');
    }

    public function destroy(\App\Models\Shift $shift)
    {
        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'Shift berhasil dihapus');
    }
}
