<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = [
            [
                'id' => 1,
                'nama' => 'Ahmad Fauzan',
                'nim' => '31240001',
                'email' => 'ahmad@example.com',
                'nomor_telepon' => '081234567890',
                'alamat' => 'Surabaya',
                'status' => 'aktif',
            ],
            [
                'id' => 2,
                'nama' => 'Siti Aminah',
                'nim' => '31240002',
                'email' => 'siti@example.com',
                'nomor_telepon' => '081298765432',
                'alamat' => 'Sidoarjo',
                'status' => 'aktif',
            ],
            [
                'id' => 3,
                'nama' => 'Budi Santoso',
                'nim' => '31240003',
                'email' => 'budi@example.com',
                'nomor_telepon' => '082112345678',
                'alamat' => 'Gresik',
                'status' => 'nonaktif',
            ],
        ];

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request)
    {
        $validated = $request->validated();

        return redirect()
            ->route('members.index')
            ->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        return "MemberController@show, id: {$id}";
    }

    public function edit(string $id)
    {
        return "MemberController@edit, id: {$id}";
    }

    public function update(Request $request, string $id)
    {
        return "MemberController@update, id: {$id}";
    }

    public function destroy(string $id)
    {
        return "MemberController@destroy, id: {$id}";
    }
}