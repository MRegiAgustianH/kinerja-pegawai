<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('divisi')->latest()->paginate(10);

        return view('admin.user.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:4',
            'role' => 'required|in:admin,manajer,pimpinan',
            'id_divisi' => 'nullable|exists:divisi,id',
        ]);
        $data['password'] = bcrypt($data['password']);
        if ($data['role'] !== 'manajer') {
            $data['id_divisi'] = null;
        }
        User::create($data);

        return redirect()->route('admin.user.index')->with('success', 'Akun ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:4',
            'role' => 'required|in:admin,manajer,pimpinan',
            'id_divisi' => 'nullable|exists:divisi,id',
        ]);
        if ($data['role'] !== 'manajer') {
            $data['id_divisi'] = null;
        }
        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);

        return redirect()->route('admin.user.index')->with('success', 'Akun diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'Akun dihapus.');
    }
}