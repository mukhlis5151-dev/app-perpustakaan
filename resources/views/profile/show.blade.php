@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <h1>Profil Saya</h1>

    <table>
        <tr><th style="width: 160px;">Nama</th><td>{{ $user->name }}</td></tr>
        <tr><th>Email</th><td>{{ $user->email }}</td></tr>
        <tr><th>Role</th><td>{{ ucfirst($user->role) }}</td></tr>
    </table>

    <h2 style="margin-top: 32px;">Ganti Password</h2>

    <form action="{{ route('profil.password') }}" method="POST" style="max-width: 400px;">
        @csrf
        @method('PUT')

        <label for="password_lama" style="display:block; margin-top:12px; font-weight:bold;">Password Lama</label>
        <input type="password" name="password_lama" id="password_lama" style="width:100%; padding:6px;">
        @error('password_lama')
            <div style="color:#b91c1c; font-size:14px;">{{ $message }}</div>
        @enderror

        <label for="password" style="display:block; margin-top:12px; font-weight:bold;">Password Baru</label>
        <input type="password" name="password" id="password" style="width:100%; padding:6px;">
        @error('password')
            <div style="color:#b91c1c; font-size:14px;">{{ $message }}</div>
        @enderror

        <label for="password_confirmation" style="display:block; margin-top:12px; font-weight:bold;">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" id="password_confirmation" style="width:100%; padding:6px;">

        <button type="submit" class="btn" style="margin-top:20px;">Ganti Password</button>
    </form>
@endsection