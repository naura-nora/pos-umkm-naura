@extends('layouts.adminlte_login')

@section('content')
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    

        <div class="card">
            <div class="card-body login-card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input id="name" type="text" name="name" :value="old('name')" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="Nama Lengkap" required autofocus autocomplete="name">
                        @error('name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" :value="old('email')" 
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="Email" required autocomplete="username">
                        @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Password" required autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" 
                               class="form-control" 
                               placeholder="Konfirmasi Password" required autocomplete="new-password">
                    </div>

                    <!-- Buttons -->
                    <div class="row mt-3">
                        <div class="col-6">
                            <button type="button" onclick="window.location='{{ route('login') }}'" 
                                    class="btn btn-secondary btn-block">Batal</button>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection