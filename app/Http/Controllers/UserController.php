<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id_user', 'desc')->get();
        return view('pages.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,petugas,owner',
            'shift'    => 'nullable|in:1,2,3|required_if:role,petugas',
            'status'   => 'nullable|in:aktif,nonaktif', // 🔥 FIX DISINI
        ]);

        // logic shift
        $shift = $request->role === 'petugas' ? $request->shift : null;

        // override hanya untuk petugas
        $isOverride = $request->role === 'petugas'
            ? $request->boolean('status_override')
            : false;

        // 🔥 kalau tidak override → status default aja
        $status = $isOverride
            ? $request->status
            : 'nonaktif';

        User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => bcrypt($request->password),
            'role'            => $request->role,
            'shift'           => $shift,
            'status'          => $status,
            'status_override' => $isOverride,
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $id . ',id_user',
            'role'   => 'required|in:admin,petugas,owner',
            'shift'  => 'nullable|in:1,2,3|required_if:role,petugas',
            'status' => 'nullable|in:aktif,nonaktif', // 🔥 FIX
        ]);

        $shift = $request->role === 'petugas' ? $request->shift : null;

        $isOverride = $request->role === 'petugas'
            ? $request->boolean('status_override')
            : false;

        // 🔥 kalau tidak override → pakai status lama aja
        $status = $isOverride
            ? $request->status
            : $user->status;

        $data = [
            'name'            => $request->name,
            'email'           => $request->email,
            'role'            => $request->role,
            'shift'           => $shift,
            'status'          => $status,
            'status_override' => $isOverride,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function print($role)
    {
        $validRoles = ['admin', 'petugas', 'owner'];

        if (!in_array($role, $validRoles)) {
            abort(404);
        }

        $users = User::where('role', $role)
            ->orderBy('name')
            ->get();

        return view('pages.users.print', compact('users', 'role'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id_user === auth()->user()->id_user) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus!');
    }
}
