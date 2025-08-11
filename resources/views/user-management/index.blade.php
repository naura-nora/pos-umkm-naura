@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">User & Role Management</h3>
            <div class="card-tools">
                <a href="{{ route('users.create') }}" class="btn btn-light">
                    <i class="fas fa-plus"></i> Tambah Akun
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-sm password-field" 
                                       value="{{ $user->password_plain ?? $user->password }}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-sm toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->getRoleNames()->first() ?? 'Pelanggan' }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.toggle-password').click(function() {
        const input = $(this).closest('.input-group').find('.password-field');
        const icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});
</script>
@endsection