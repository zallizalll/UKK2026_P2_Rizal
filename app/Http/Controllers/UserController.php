<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use LogAktivitasTrait;

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
            'status'   => 'nullable|in:aktif,nonaktif',
        ]);

        $shift = $request->role === 'petugas' ? $request->shift : null;

        $isOverride = $request->role === 'petugas'
            ? $request->boolean('status_override')
            : false;

        $status = $isOverride ? $request->status : 'nonaktif';

        User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => bcrypt($request->password),
            'role'            => $request->role,
            'shift'           => $shift,
            'status'          => $status,
            'status_override' => $isOverride,
        ]);

        $shiftInfo = $shift ? ' shift ' . $shift : '';
        $this->log('Tambah user: ' . $request->name . ' (' . ucfirst($request->role) . $shiftInfo . ') — status: ' . $status);

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
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        $shift = $request->role === 'petugas' ? $request->shift : null;

        $isOverride = $request->role === 'petugas'
            ? $request->boolean('status_override')
            : false;

        $status = $isOverride ? $request->status : $user->status;

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

        $shiftInfo = $shift ? ' shift ' . $shift : '';
        $this->log('Update user: ' . $request->name . ' (' . ucfirst($request->role) . $shiftInfo . ') — status: ' . $status);

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id_user === auth()->user()->id_user) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $this->log('Hapus user: ' . $user->name . ' (' . ucfirst($user->role) . ') — ' . $user->email);

        $user->delete();

        return back()->with('success', 'User berhasil dihapus!');
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
}
