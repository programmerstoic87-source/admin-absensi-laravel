<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $locations = Location::with('branch')
            ->when($user->jabatan !== 'Admin' && $user->jabatan !== 'Chief', fn($q) => $q->where('branch_id', $user->branch_id))
            ->get();

        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('locations.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'nama_lokasi' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius_meter' => 'required|integer',
        ]);

        Location::create([
            'id' => (string) Str::uuid(),
            'branch_id' => $request->branch_id,
            'nama_lokasi' => $request->nama_lokasi,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'radius_meter' => $request->radius_meter,
            'alamat' => $request->alamat,
            'is_active' => true,
        ]);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil ditambah');
    }

    public function edit(Location $location)
    {
        $branches = Branch::all();
        return view('locations.edit', compact('location', 'branches'));
    }

    public function update(Request $request, Location $location)
    {
        $location->update($request->all());

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diupdate');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil dihapus');
    }
}
