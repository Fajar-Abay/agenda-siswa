<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas; // Import model Kelas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Validation\Rule; // Untuk unique rule pada email

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua pengguna dengan relasi kelas di-eager load, paginasi
        $users = User::with('kelas')->paginate(10); // 10 user per halaman
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua kelas untuk dropdown
        $kelas = Kelas::all();
        return view('users.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' akan mencari password_confirmation
            'role' => ['required', Rule::in(['admin', 'kalas'])], // Hanya boleh 'admin' atau 'kalas'
            'kelas_id' => 'nullable|exists:kelas,id', // kelas_id bisa null, tapi jika ada harus exist di tabel kelas
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password sebelum disimpan
            'role' => $request->role,
            'kelas_id' => $request->kelas_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Biasanya tidak ada halaman show terpisah untuk CRUD admin sederhana
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Ambil semua kelas untuk dropdown
        $kelas = Kelas::all();
        return view('users.edit', compact('user', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Email harus unik, kecuali untuk user ini sendiri
            ],
            'role' => ['required', Rule::in(['admin', 'kalas'])],
            'kelas_id' => 'nullable|exists:kelas,id',
        ];

        // Hanya validasi password jika ada inputnya (opsional saat update)
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->kelas_id = $request->kelas_id;

        // Update password hanya jika ada input baru
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Otorisasi: Admin tidak bisa menghapus dirinya sendiri
        if (auth()->user()->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
