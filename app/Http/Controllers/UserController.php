<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('role')->select(['id', 'name', 'email', 'role_id']);
            return DataTables::of($data)
                ->addColumn('role', function ($row) {
                    return $row->role->name ?? '-';
                })
                ->addColumn('actions', function ($user) {
                    $editUrl = route('users.edit', ['user' => $user->id]);
                    $deleteUrl = route('users.destroy', ['user' => $user->id]);

                    $editLink = '<a href="' . $editUrl . '" class="btn btn-warning me-1"><span class="mdi mdi-pencil me-2"></span>Edit</a>';
                    $deleteLink = '<a href="javascript:;" onclick="deleteUser(`' . $deleteUrl . '`)" class="btn btn-danger me-1"><span class="mdi mdi-delete me-2"></span>Delete</a>';

                    return $editLink . ' ' . $deleteLink; // Tambahkan spasi antara tombol edit dan delete
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('users.index', [
            'title' => 'Users'
        ]);
    }

    public function create()
    {
        //logic
        $roles = Role::all(); // Ambil semua data role dari tabel role
        return view('users.create', ['roles' => $roles, 'title' => 'Tambah User Baru']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:3',
            'role_id' => 'required|exists:roles,id', // Validasi langsung ke tabel roles
        ]);

        // Buat instance user baru
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $request->input('role_id'), // Simpan role_id langsung ke dalam users
        ]);

        // Simpan user ke database
        $user->save();

        $role = Role::findOrFail($request->input('role_id'));

        // Assign role ke user menggunakan package spatie/laravel-permission
        $user->assignRole($role);

        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan!');
    }


    public function edit(User $user)
    {
        $roles = Role::all();
        $title = 'Edit';
        return view('users.edit', compact('user', 'roles', 'title'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $userData = $request->only(['name', 'email', 'password','role_id']);
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->input('password'));
        }

        $role = Role::findOrFail($request->input('role_id'));

        $user->fill($userData); // Menggunakan fill() untuk mengisi data pengguna
        $user->save();

        // Hapus peran (role) lama dan tetapkan peran yang baru
        $user->removeRole($user->role); // Hapus peran lama
        $user->assignRole($role); // Assign peran baru

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }


    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User berhasil dihapus'], 200);
    }
}
