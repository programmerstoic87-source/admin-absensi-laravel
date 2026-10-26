<?php

namespace App\Http\Controllers;

use App\Models\PatrolCheckpoint;
use App\Models\Branch;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatrolCheckpointController extends Controller
{
    public function index()
    {
        $checkpoints = PatrolCheckpoint::with(['branch', 'location'])->get();

        return view('patrol-checkpoints.index', compact('checkpoints'));
    }

    public function create()
    {
        $branches = Branch::all();
        $locations = Location::all();

        return view('patrol-checkpoints.create', compact('branches', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'location_id' => 'required|exists:locations,id',
            'nama' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius_meter' => 'required|integer',
            'urutan' => 'required|integer',
        ]);

        PatrolCheckpoint::create([
            'id' => (string) Str::uuid(),
            'branch_id' => $request->branch_id,
            'location_id' => $request->location_id,
            'nama' => $request->nama,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'radius_meter' => $request->radius_meter,
            'urutan' => $request->urutan,
            'is_active' => true,
        ]);

        return redirect()->route('patrol-checkpoints.index')->with('success', 'Checkpoint berhasil ditambah');
    }

    public function edit(PatrolCheckpoint $patrolCheckpoint)
    {
        $branches = Branch::all();
        $locations = Location::all();

        return view('patrol-checkpoints.edit', compact('patrolCheckpoint', 'branches', 'locations'));
    }

    public function update(Request $request, PatrolCheckpoint $patrolCheckpoint)
    {
        $patrolCheckpoint->update($request->all());

        return redirect()->route('patrol-checkpoints.index')->with('success', 'Checkpoint berhasil diupdate');
    }

    public function destroy(PatrolCheckpoint $patrolCheckpoint)
    {
        $patrolCheckpoint->delete();

        return redirect()->route('patrol-checkpoints.index')->with('success', 'Checkpoint berhasil dihapus');
    }
}
