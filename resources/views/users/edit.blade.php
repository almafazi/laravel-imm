@extends('layouts.master')

@section('style')
    <style>
        .no-rezise {
            resize: none;
            height: 15vh;
        }
    </style>
@endsection
@section('content')
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Gudang / List User /</span>
        {{ $title }}
    </h5>
    <!-- Form controls -->

    <form action="{{ route('users.update', ['user' => $user->id]) }}" method="post">
        @csrf
        @method('PUT')
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Edit User</h5>
                <div class="card-body demo-vertical-spacing demo-only-element">
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Input username"
                            value="{{ $user->name }}" required autocomplete="off" />
                        <label for="name">Username</label>
                        @error('name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Input email"
                            value="{{ $user->email }}" required autocomplete="off" />
                        <label for="email">Email</label>
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Input password" value="{{ $user->password }}" autocomplete="off" />
                        <label for="password">Password</label>
                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <select class="form-control" id="role_id" name="role_id" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @if ($role->id === $user->role_id) selected @endif>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="role_id">Role</label>
                        @error('role_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-between">
            <a href="{{ route('users.index') }}" class="btn btn-label-secondary">
                <span class="mdi mdi-arrow-left me-2"></span>
                Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <span class="mdi mdi-content-save me-2"></span>
                Simpan Data
            </button>
        </div>
    </form>
@endsection
