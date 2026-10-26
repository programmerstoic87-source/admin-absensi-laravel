<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $branches = Branch::query()
            ->when($user->jabatan !== 'Admin' && $user->jabatan !== 'Chief', fn($q) => $q->where('id', $user->branch_id))
            ->get();

        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        Branch::create([
            'id' => (string) Str::uuid(),
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'is_active' => true,
        ]);

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil ditambah');
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $branch->update($request->only(['nama_cabang', 'alamat', 'is_active']));

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil diupdate');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil dihapus');
    }
}
