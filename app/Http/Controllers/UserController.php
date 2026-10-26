<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $users = User::with('branch')
            ->when($user->jabatan !== 'Admin' && $user->jabatan !== 'Chief', fn($q) => $q->where('branch_id', $user->branch_id))
            ->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('users.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'nik' => 'required|string|unique:users,nik',
            'nama' => 'required|string',
            'jabatan' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'id' => (string) Str::uuid(),
            'branch_id' => $request->branch_id,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_active' => true,
            'face_registered' => false,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $branches = Branch::all();
        return view('users.edit', compact('user', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->only('branch_id', 'nik', 'nama', 'jabatan', 'email', 'is_active');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
